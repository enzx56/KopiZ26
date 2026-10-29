<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PreOrderController extends Controller
{
    public function index()
    {
        $preOrders = PreOrder::with('products')->withCount('orders')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pre_orders.index', compact('preOrders'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.pre_orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'pickup_at' => 'nullable|date',
            'quota_units' => 'required|integer|min:1',
            'status' => 'required|in:DRAFT,SCHEDULED,OPEN,CLOSED,SOLD_OUT,COMPLETED,CANCELLED',
            'instruction_notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'required|integer|min:0',
        ]);

        $slug = Str::slug($request->name);
        $count = PreOrder::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $preOrder = PreOrder::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'banner_path' => $request->banner_url ?: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&auto=format&fit=crop&q=80',
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'pickup_at' => $request->pickup_at,
            'quota_units' => $request->quota_units,
            'reserved_units' => 0,
            'status' => $request->status,
            'instruction_notes' => $request->instruction_notes,
            'published_at' => now(),
            'created_by' => Auth::id(),
        ]);

        foreach ($request->products as $p) {
            $preOrder->products()->attach($p['product_id'], [
                'price' => $p['price'],
                'max_quantity_per_order' => 10,
            ]);
        }

        return redirect()->route('admin.pre-orders.index')->with('success', 'Batch Pre-Order berhasil dibuat.');
    }

    public function edit(PreOrder $preOrder)
    {
        $products = Product::active()->get();
        $selectedProductIds = $preOrder->products->pluck('id')->all();
        return view('admin.pre_orders.edit', compact('preOrder', 'products', 'selectedProductIds'));
    }

    public function update(Request $request, PreOrder $preOrder)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
            'pickup_at' => 'nullable|date',
            'quota_units' => 'required|integer|min:' . $preOrder->reserved_units,
            'status' => 'required|in:DRAFT,SCHEDULED,OPEN,CLOSED,SOLD_OUT,COMPLETED,CANCELLED',
            'instruction_notes' => 'nullable|string',
        ]);

        $preOrder->update([
            'name' => $request->name,
            'description' => $request->description,
            'banner_path' => $request->filled('banner_url') ? $request->banner_url : $preOrder->banner_path,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'pickup_at' => $request->pickup_at,
            'quota_units' => $request->quota_units,
            'status' => $request->status,
            'instruction_notes' => $request->instruction_notes,
        ]);

        return redirect()->route('admin.pre-orders.index')->with('success', 'Pre-Order berhasil diperbarui.');
    }

    public function destroy(PreOrder $preOrder)
    {
        if ($preOrder->orders()->count() > 0) {
            return back()->withErrors(['msg' => 'Pre-Order tidak dapat dihapus karena sudah memiliki riwayat pesanan. Anda dapat mengubah statusnya menjadi CANCELLED atau CLOSED.']);
        }

        $preOrder->products()->detach();
        $preOrder->delete();

        return redirect()->route('admin.pre-orders.index')->with('success', 'Pre-Order berhasil dihapus.');
    }
}
