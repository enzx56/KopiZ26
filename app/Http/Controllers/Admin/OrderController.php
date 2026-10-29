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

        // Persiapan nomor WhatsApp yang dinormalisasi
        $customerPhone = preg_replace('/[^0-9]/', '', $order->customer->phone ?? '');
        if (str_starts_with($customerPhone, '0')) {
            $customerPhone = '62' . substr($customerPhone, 1);
        }

        $trackUrl = route('order.track', $order->order_code);
        $totalFormatted = 'Rp' . number_format($order->total_amount, 0, ',', '.');

        // Template Notifikasi WhatsApp berdasarkan status murni via WA
        $waTemplates = [
            'CONFIRMED' => "Halo Kak *{$order->customer->name}*! Pesanan Anda di *KopiZ* (*#{$order->order_code}*) senilai *{$totalFormatted}* telah *DIKONFIRMASI*. Pesanan sedang kami siapkan! ☕\n\nPantau status pesanan: {$trackUrl}\n\nTerima kasih!",
            'PREPARING' => "Halo Kak *{$order->customer->name}*! Pesanan KopiZ Anda (*#{$order->order_code}*) sedang *DIRACIK* oleh barista. ☕✨",
            'COMPLETED' => "Halo Kak *{$order->customer->name}*! Pesanan KopiZ Anda (*#{$order->order_code}*) telah *SELESAI*. Selamat menikmati kopi kami! ☕✨",
            'GENERAL' => "Halo Kak *{$order->customer->name}*, kami dari *KopiZ* terkait pesanan Anda (*#{$order->order_code}*)...",
        ];

        return view('admin.orders.show', compact('order', 'customerPhone', 'waTemplates'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Status alur pesanan yang disederhanakan tanpa pickup
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,PREPARING,COMPLETED,CANCELLED',
            'note' => 'nullable|string|max:500',
        ]);

        $fromStatus = $order->status;
        $toStatus = $request->status;

        if ($fromStatus === $toStatus) {
            return back()->with('info', 'Status tidak berubah.');
        }

        if ($fromStatus === 'COMPLETED' && $toStatus !== 'COMPLETED') {
            return back()->withErrors(['msg' => 'Pesanan yang telah COMPLETED tidak dapat diubah statusnya kembali.']);
        }

        $order->status = $toStatus;

        if ($toStatus === 'CONFIRMED' && !$order->confirmed_at) {
            $order->confirmed_at = now();
        } elseif ($toStatus === 'COMPLETED' && !$order->completed_at) {
            $order->completed_at = now();
        } elseif ($toStatus === 'CANCELLED' && !$order->cancelled_at) {
            $order->cancelled_at = now();
            if ($order->order_type === 'PRE_ORDER' && $order->preOrder) {
                $orderedUnits = $order->items->sum('quantity');
                $order->preOrder->decrement('reserved_units', $orderedUnits);
                if ($order->preOrder->status === 'SOLD_OUT') {
                    $order->preOrder->update(['status' => 'OPEN']);
                }
            }
        }

        $order->save();

        $defaultNote = match($toStatus) {
            'CONFIRMED' => 'Pesanan dikonfirmasi oleh Admin via WhatsApp.',
            'PREPARING' => 'Pesanan sedang diracik oleh Barista.',
            'COMPLETED' => 'Pesanan telah selesai diproses.',
            'CANCELLED' => 'Pesanan dibatalkan.',
            default => "Status diubah dari {$fromStatus} ke {$toStatus}",
        };

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => Auth::id(),
            'note' => $request->note ?: $defaultNote,
            'created_at' => now(),
        ]);

        return back()
            ->with('success', "Status pesanan {$order->order_code} berhasil diperbarui menjadi {$toStatus}.")
            ->with('just_updated_status', $toStatus);
    }

    public function printReceipt(Order $order)
    {
        $order->load(['customer', 'items', 'preOrder']);
        return view('admin.orders.print', compact('order'));
    }
}
