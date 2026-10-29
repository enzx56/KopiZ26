<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PreOrder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        // Definisi Metrik sesuai PRD Bagian 18
        $totalOrdersCount = (clone $query)->count();
        $completedOrders = (clone $query)->where('status', 'COMPLETED');
        $completedRevenue = (clone $completedOrders)->sum('total_amount');
        $completedCount = (clone $completedOrders)->count();

        $grossOrderValue = (clone $query)->where('status', '!=', 'CANCELLED')->sum('total_amount');
        $cancelledOrdersCount = (clone $query)->where('status', 'CANCELLED')->count();

        $filteredOrders = (clone $query)->with(['customer', 'items'])
            ->orderBy('order_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'filteredOrders',
            'totalOrdersCount',
            'completedRevenue',
            'completedCount',
            'grossOrderValue',
            'cancelledOrdersCount'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Order::with(['customer', 'items']);

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->orderBy('order_date', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Laporan_Transaksi_KopiZ_' . now()->format('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk kompatibilitas sempurna Microsoft Excel (PRD Section 18)
            fputs($handle, "\xEF\xBB\xBF");

            // Header Kolom
            fputcsv($handle, [
                'Kode Pesanan',
                'Tanggal Pesanan',
                'Nama Pelanggan',
                'Nomor WhatsApp',
                'Jenis Pesanan',
                'Daftar Item & Kuantitas',
                'Total Unit',
                'Subtotal (IDR)',
                'Total Akhir (IDR)',
                'Status Pesanan',
                'Jadwal Pengambilan',
                'Catatan Pelanggan'
            ]);

            foreach ($orders as $order) {
                $itemList = [];
                $totalUnits = 0;
                foreach ($order->items as $item) {
                    $itemList[] = "{$item->product_name_snapshot} ({$item->quantity}x)";
                    $totalUnits += $item->quantity;
                }

                fputcsv($handle, [
                    $order->order_code,
                    $order->order_date ? $order->order_date->format('Y-m-d H:i') : '-',
                    $order->customer ? $order->customer->name : '-',
                    $order->customer ? "'" . $order->customer->phone : '-',
                    $order->order_type,
                    implode('; ', $itemList),
                    $totalUnits,
                    $order->subtotal,
                    $order->total_amount,
                    $order->status,
                    $order->pickup_at ?: '-',
                    $order->customer_notes ?: '-'
                ]);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
