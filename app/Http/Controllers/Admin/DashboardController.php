<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        // Metrik Realtime
        $todayOrdersCount = Order::where('created_at', '>=', $today)->count();
        $pendingOrdersCount = Order::where('status', 'PENDING')->count();
        $processingOrdersCount = Order::whereIn('status', ['CONFIRMED', 'PREPARING'])->count();
        $completedOrdersCount = Order::where('status', 'COMPLETED')->count();

        $activePreOrdersCount = PreOrder::where('status', 'OPEN')->count();
        $activeProductsCount = Product::active()->count();

        // Nilai Transaksi Order (Total Nilai Order Selesai)
        $completedRevenue = Order::where('status', 'COMPLETED')->sum('total_amount');
        $allOrdersTotal = Order::where('status', '!=', 'CANCELLED')->sum('total_amount');

        // Pesanan Terbaru (10 order terakhir)
        $recentOrders = Order::with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Produk Terlaris
        $topProducts = OrderItem::select('product_name_snapshot', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name_snapshot')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'completedOrdersCount',
            'activePreOrdersCount',
            'activeProductsCount',
            'completedRevenue',
            'allOrdersTotal',
            'recentOrders',
            'topProducts'
        ));
    }
}
