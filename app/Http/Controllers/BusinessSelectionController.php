<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessSelectionController extends Controller
{
    public function show()
    {
        // If user already has a business type, redirect to dashboard
        if (Auth::user()->business_type) {
            return redirect()->route('dashboard');
        }

        $businessTypes = [
            'Kuliner' => [
                'icon' => 'utensils',
                'categories' => ['Makanan', 'Minuman', 'Snack']
            ],
            'Fashion dan Aksesoris' => [
                'icon' => 'shirt',
                'categories' => ['Atasan (Topwear)', 'Bawahan (Bottomwear)', 'Dress', 'Luaran (Outerwear)', 'Hijab', 'Tas', 'Sepatu', 'Sandal', 'Aksesoris']
            ],
            'Kecantikan dan Perawatan' => [
                'icon' => 'sparkles',
                'categories' => ['Skincare', 'Make up', 'Bodycare', 'Haircare']
            ],
            'Kebutuhan harian' => [
                'icon' => 'shopping-cart',
                'categories' => ['Makanan & Minuman', 'Produk Kebersihan', 'Kebutuhan Rumah Tangga', 'Perlengkapan Bayi & Anak', 'Produk Kesehatan', 'Frozen Food']
            ],
            'Kerajinan Tangan' => [
                'icon' => 'palette',
                'categories' => ['Aksesoris Handmade', 'Dekorasi Rumah', 'Produk Rajut', 'Produk Kayu', 'Produk Anyaman', 'Souvenir & Gift', 'Produk Custom']
            ],
        ];

        return view('auth.select-business', compact('businessTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string',
        ]);

        $user = Auth::user();
        $type = $request->business_type;

        $businessTypes = [
            'Kuliner' => ['Makanan', 'Minuman', 'Snack'],
            'Fashion dan Aksesoris' => ['Atasan (Topwear)', 'Bawahan (Bottomwear)', 'Dress', 'Luaran (Outerwear)', 'Hijab', 'Tas', 'Sepatu', 'Sandal', 'Aksesoris'],
            'Kecantikan dan Perawatan' => ['Skincare', 'Make up', 'Bodycare', 'Haircare'],
            'Kebutuhan harian' => ['Makanan & Minuman', 'Produk Kebersihan', 'Kebutuhan Rumah Tangga', 'Perlengkapan Bayi & Anak', 'Produk Kesehatan', 'Frozen Food'],
            'Kerajinan Tangan' => ['Aksesoris Handmade', 'Dekorasi Rumah', 'Produk Rajut', 'Produk Kayu', 'Produk Anyaman', 'Souvenir & Gift', 'Produk Custom'],
        ];

        if (!isset($businessTypes[$type])) {
            return back()->withErrors(['business_type' => 'Jenis usaha tidak valid.']);
        }

        $user->update(['business_type' => $type]);

        // Clear existing categories and create new ones
        $user->categories()->delete();
        foreach ($businessTypes[$type] as $categoryName) {
            $user->categories()->create(['name' => $categoryName]);
        }

        return redirect()->route('dashboard')->with('success', 'Jenis usaha berhasil dipilih!');
    }
}
