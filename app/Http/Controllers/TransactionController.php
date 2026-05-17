<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Auth::user()->products()->where('stock', '>', 0)->get();
        return view('pages.transactions.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'tax' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qris',
            'reference_number' => 'nullable|string|max:100',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        $payment_proof = null;
        if ($request->hasFile('payment_proof')) {
            $payment_proof = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        return DB::transaction(function () use ($request, $payment_proof) {
            $totalAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk: {$product->name}");
                }

                $priceAfterDiscount = $product->price - $product->discount;
                $itemSubtotal = ($priceAfterDiscount * (1 + $product->tax / 100)) * $item['quantity'];
                $totalAmount += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_time' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];

                // Update Stock
                $product->decrement('stock', $item['quantity']);
            }

            $tax = 0; // Tax is now calculated per item
            $netAmount = $totalAmount;

            $transaction = Auth::user()->transactions()->create([
                'total_amount' => $totalAmount,
                'tax' => $tax,
                'net_amount' => $netAmount,
                'status' => 'completed',
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_proof' => $payment_proof,
            ]);

            foreach ($itemsData as $data) {
                $transaction->items()->create($data);
            }

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diproses.');
        });
    }
}
