<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
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
            'expired_at' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Set default threshold if not provided
        $data['min_stock_threshold'] = $data['min_stock_threshold'] ?? 5;

        // Handle empty expired_at
        if (empty($data['expired_at'])) {
            $data['expired_at'] = null;
        }

        $data['discount'] = $data['discount'] ?? 0;
        $data['tax'] = $data['tax'] ?? 0;

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
            'expired_at' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        // Set default threshold if not provided
        $data['min_stock_threshold'] = $data['min_stock_threshold'] ?? 5;

        // Handle empty expired_at
        if (empty($data['expired_at'])) {
            $data['expired_at'] = null;
        }

        $data['discount'] = $data['discount'] ?? 0;
        $data['tax'] = $data['tax'] ?? 0;

        $product->update($data);

        return redirect()->route('stock.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('stock.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function expiry()
    {
        $user = Auth::user();
        $products = $user->products()
            ->with('category')
            ->whereNotNull('expired_at')
            ->orderBy('expired_at', 'asc')
            ->get();

        // Group by status
        $expired = $products->filter(fn($p) => $p->expiryStatus() === 'expired');
        $critical = $products->filter(fn($p) => $p->expiryStatus() === 'critical');
        $warning = $products->filter(fn($p) => $p->expiryStatus() === 'warning');
        $safe = $products->filter(fn($p) => $p->expiryStatus() === 'safe');

        $stats = [
            'total' => $products->count(),
            'expired' => $expired->count(),
            'critical' => $critical->count(),
            'warning' => $warning->count(),
            'safe' => $safe->count(),
        ];

        return view('pages.expiry.index', compact('products', 'expired', 'critical', 'warning', 'safe', 'stats'));
    }
}
