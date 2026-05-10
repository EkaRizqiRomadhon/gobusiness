<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->categories()->count() <= 1) {
            $user->categories()->firstOrCreate(['name' => 'Makanan']);
            $user->categories()->firstOrCreate(['name' => 'Minuman']);
            $user->categories()->firstOrCreate(['name' => 'Snack']);
        }
        
        $products = $user->products()->with('category')->latest()->get();
        $categories = $user->categories;
        return view('pages.stock.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'min_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Set default threshold if not provided
        $data['min_stock_threshold'] = $data['min_stock_threshold'] ?? 5;

        Auth::user()->products()->create($data);

        return redirect()->route('stock.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'min_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Set default threshold if not provided
        $data['min_stock_threshold'] = $data['min_stock_threshold'] ?? 5;

        $product->update($data);

        return redirect()->route('stock.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('stock.index')->with('success', 'Produk berhasil dihapus.');
    }
}
