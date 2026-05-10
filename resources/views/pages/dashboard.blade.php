@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Welcome Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">Halo, {{ Auth::user()->name }} 👋</h2>
            <p class="text-surface-500">Berikut adalah ringkasan performa bisnis Anda hari ini.</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium text-surface-500">{{ now()->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Stats Grid (3 Columns now) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-surface-500">Penjualan Hari Ini</p>
            <h3 class="text-2xl font-bold text-surface-900 mt-1">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
            </div>
            <p class="text-sm font-medium text-surface-500">Transaksi Hari Ini</p>
            <h3 class="text-2xl font-bold text-surface-900 mt-1">{{ $stats['transaction_count'] }}</h3>
        </div>

        <div class="bg-white p-6 rounded-2xl border {{ $stats['low_stock_count'] > 0 ? 'border-orange-200 bg-orange-50/30' : 'border-surface-200' }} shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 {{ $stats['low_stock_count'] > 0 ? 'bg-orange-100 text-orange-600' : 'bg-indigo-50 text-indigo-600' }} rounded-xl flex items-center justify-center">
                    <i data-lucide="{{ $stats['low_stock_count'] > 0 ? 'alert-triangle' : 'package' }}" class="w-6 h-6"></i>
                </div>
                @if($stats['low_stock_count'] > 0)
                    <span class="animate-pulse flex h-3 w-3 rounded-full bg-orange-500"></span>
                @endif
            </div>
            <p class="text-sm font-medium text-surface-500">Total Produk</p>
            <div class="flex items-baseline justify-between mt-1">
                <h3 class="text-2xl font-bold text-surface-900">{{ $stats['total_products_count'] }} Unit</h3>
                @if($stats['low_stock_count'] > 0)
                    <span class="text-[10px] font-bold text-orange-700 bg-orange-100 px-2 py-0.5 rounded-full uppercase tracking-wider">
                        {{ $stats['low_stock_count'] }} Perlu Stok
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Charts & Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Weekly Trend Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-surface-900">Tren Penjualan 7 Hari Terakhir</h3>
                <div class="text-xs text-surface-400 font-bold uppercase tracking-wider">Omzet Harian</div>
            </div>
            
            <div class="h-64 flex items-end justify-between px-4 gap-2">
                @php $maxVal = $weeklyTrend->max('total') ?: 1; @endphp
                @foreach($weeklyTrend as $day)
                    @php $height = ($day->total / $maxVal) * 100; @endphp
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-primary-100 rounded-t-lg group-hover:bg-primary-500 transition-all relative" style="height: {{ max($height, 5) }}%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-surface-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                Rp {{ number_format($day->total, 0, ',', '.') }}
                            </div>
                        </div>
                        <p class="text-[10px] text-surface-400 font-bold mt-2 uppercase">{{ \Carbon\Carbon::parse($day->date)->format('D') }}</p>
                    </div>
                @endforeach

                @if($weeklyTrend->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center text-surface-400 text-sm">
                    Belum ada data transaksi 7 hari terakhir.
                </div>
                @endif
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
            <h3 class="text-lg font-bold text-surface-900 mb-6">Produk Terlaris</h3>
            <div class="space-y-4">
                @forelse($bestSellers as $index => $product)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-surface-100 flex items-center justify-center text-surface-500 font-bold text-xs">{{ $index + 1 }}</div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-surface-900 leading-tight">{{ $product->name }}</p>
                            <p class="text-xs text-surface-500">{{ $product->transaction_items_sum_quantity ?? 0 }} Terjual</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-primary-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-sm text-surface-400 text-center py-8">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-surface-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-surface-900">Transaksi Terbaru</h3>
            <a href="/transactions" class="text-sm font-medium text-primary-600 hover:underline">Input Transaksi</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 border-b border-surface-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Total Omzet</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-surface-500">{{ $trx->created_at->format('H:i') }} WIB</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-surface-900">Rp {{ number_format($trx->net_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Selesai</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-surface-400">Belum ada transaksi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
