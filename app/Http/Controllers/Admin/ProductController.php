<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category')->whereNull('archived_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('is_featured', 'desc')->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'price' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'status' => 'required|in:READY,PO,SOLD_OUT,INACTIVE',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
        ]);

        $imagePath = $request->image_url;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        }

        if (!$imagePath) {
            $imagePath = 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80';
        }

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'sku' => $request->sku ?: ('KPZ-' . strtoupper(Str::random(6))),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => $request->status,
            'track_inventory' => $request->boolean('track_inventory'),
            'stock_quantity' => $request->stock_quantity ?: 0,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->status !== 'INACTIVE',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'status' => 'required|in:READY,PO,SOLD_OUT,INACTIVE',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
        ]);

        $imagePath = $product->image_path;
        if ($request->filled('image_url')) {
            $imagePath = $request->image_url;
        }
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imagePath = '/storage/' . $path;
        }

        $product->update([
            'name' => $request->name,
            'sku' => $request->sku ?: $product->sku,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => $request->status,
            'track_inventory' => $request->boolean('track_inventory'),
            'stock_quantity' => $request->stock_quantity ?: 0,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->status !== 'INACTIVE',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Soft-archival to protect historical orders (PRD Section 8)
        $product->update([
            'archived_at' => now(),
            'is_active' => false,
            'status' => 'INACTIVE'
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diarsipkan.');
    }

    public function toggleStatus(Product $product)
    {
        $newStatus = $product->status === 'READY' ? 'SOLD_OUT' : 'READY';
        $product->update(['status' => $newStatus]);

        return back()->with('success', "Status produk diubah menjadi {$newStatus}.");
    }
}
