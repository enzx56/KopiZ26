<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
