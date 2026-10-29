<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items', 'preOrder']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('order_code', 'like', "%{$q}%")
                  ->orWhereHas('customer', function ($c) use ($q) {
                      $c->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'preOrder', 'statusHistories.user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,PREPARING,READY_FOR_PICKUP,COMPLETED,CANCELLED',
            'note' => 'nullable|string|max:500',
        ]);

        $fromStatus = $order->status;
        $toStatus = $request->status;

        if ($fromStatus === $toStatus) {
            return back()->with('info', 'Status tidak berubah.');
        }

        // Validasi transisi status (PRD Section 12)
        if ($fromStatus === 'COMPLETED' && $toStatus !== 'COMPLETED') {
            return back()->withErrors(['msg' => 'Pesanan yang telah COMPLETED tidak dapat diubah statusnya kembali tanpa verifikasi khusus.']);
        }

        $order->status = $toStatus;

        if ($toStatus === 'CONFIRMED' && !$order->confirmed_at) {
            $order->confirmed_at = now();
        } elseif ($toStatus === 'COMPLETED' && !$order->completed_at) {
            $order->completed_at = now();
        } elseif ($toStatus === 'CANCELLED' && !$order->cancelled_at) {
            $order->cancelled_at = now();
            // Kembalikan kuota PO jika pesanan dibatalkan
            if ($order->order_type === 'PRE_ORDER' && $order->preOrder) {
                $orderedUnits = $order->items->sum('quantity');
                $order->preOrder->decrement('reserved_units', $orderedUnits);
                if ($order->preOrder->status === 'SOLD_OUT') {
                    $order->preOrder->update(['status' => 'OPEN']);
                }
            }
        }

        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => Auth::id(),
            'note' => $request->note ?: "Status diubah dari {$fromStatus} ke {$toStatus}",
            'created_at' => now(),
        ]);

        return back()->with('success', "Status pesanan {$order->order_code} berhasil diperbarui menjadi {$toStatus}.");
    }

    public function printReceipt(Order $order)
    {
        $order->load(['customer', 'items', 'preOrder']);
        return view('admin.orders.print', compact('order'));
    }
}
