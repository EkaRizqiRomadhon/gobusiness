@extends('layouts.app')

@section('title', 'Analitik Bisnis')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">Analitik & Performa</h2>
            <p class="text-surface-500">Visualisasi tren dan perbandingan performa produk Anda.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-white p-4 rounded-2xl border border-surface-200 shadow-sm flex items-center">
                <div class="mr-4">
                    <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Total Omzet</p>
                    <p class="text-lg font-bold text-surface-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Weekly Sales Trend (Line Chart Visual) -->
        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-surface-900">Tren Omzet (7 Hari Terakhir)</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold {{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                    </span>
                    <span class="text-[10px] text-surface-400 font-bold uppercase">vs Minggu Lalu</span>
                </div>
            </div>
            <div class="h-80 flex justify-between px-2 gap-2 bg-surface-50 p-6 rounded-xl relative">
                @php 
                    $recentTrends = $trends->take(-7);
                    $maxTrend = $recentTrends->max('total') ?: 1; 
                @endphp
                @foreach($recentTrends as $day)
                    @php $h = ($day->total / $maxTrend) * 100; @endphp
                    <div class="flex-1 h-full flex flex-col justify-end items-center" style="height: 100%;">
                        <div class="relative group" style="width: 24px; height: {{ max($h, 5) }}%; background-color: #4f46e5; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-surface-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap">
                                Rp {{ number_format($day->total, 0, ',', '.') }}
                            </div>
                        </div>
                        <p class="text-[10px] text-surface-500 font-bold mt-2">{{ $day->day }}</p>
                    </div>
                @endforeach
                @if($recentTrends->isEmpty())
                    <div class="absolute inset-0 flex items-center justify-center text-surface-400 text-sm">Belum ada data transaksi.</div>
                @endif
            </div>
        </div>

        <!-- Category Comparison (Bar Chart) -->
        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
            <h3 class="text-lg font-bold text-surface-900 mb-6">Omzet per Kategori</h3>
            <div class="space-y-6">
                @php $maxCatRevenue = $categoryRevenue->max('total_revenue') ?: 1; @endphp
                @forelse($categoryRevenue as $cat)
                @php $percent = ($cat->total_revenue / $maxCatRevenue) * 100; @endphp
                <div>
                    <div class="flex justify-between mb-1.5">
                        <span class="text-sm font-bold text-surface-700">{{ $cat->name }}</span>
                        <span class="text-sm font-bold text-primary-600">Rp {{ number_format($cat->total_revenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-surface-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-primary-600 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-surface-400 py-10">Belum ada data penjualan kategori.</p>
                @endforelse
            </div>
            <div class="mt-8 p-4 bg-primary-50 rounded-xl border border-primary-100">
                <p class="text-xs text-primary-700 leading-relaxed">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1"></i>
                    Statistik ini menunjukkan kategori mana yang memberikan kontribusi omzet terbesar bagi bisnis Anda.
                </p>
            </div>
        </div>

        <!-- Top Products (New Feature in Old Style) -->
        <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-surface-200">
                <h3 class="text-lg font-bold text-surface-900">Produk Terpopuler</h3>
            </div>
            <div class="flex-1 p-6 space-y-5">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-surface-100 rounded-lg flex items-center justify-center text-surface-500 font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-surface-900 leading-tight">{{ $product->name }}</p>
                                <p class="text-[10px] text-surface-500 font-bold uppercase mt-0.5">{{ $product->sold }} Terjual</p>
                            </div>
                        </div>
                        <p class="text-sm font-bold text-primary-600">Rp {{ number_format($product->revenue, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-center text-surface-400 py-10">Belum ada data produk.</p>
                @endforelse
            </div>
        </div>

        <!-- Strategic Insight Card -->
        <div class="premium-gradient p-8 rounded-2xl text-white shadow-xl flex flex-col justify-center relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6">
                <i data-lucide="zap" class="w-8 h-8 text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-3">Insight Bisnis</h3>
            <p class="text-primary-100 leading-relaxed mb-6">
                @if($growth > 0)
                    Performa bisnis Anda meningkat <b>{{ number_format($growth, 1) }}%</b> dibanding minggu lalu. Terus pertahankan momentum ini!
                @else
                    Pastikan stok kategori terpopuler Anda selalu terjaga untuk memaksimalkan potensi penjualan harian.
                @endif
            </p>
            <div class="flex gap-3">
                <button onclick="window.location.href='/transactions'" class="px-5 py-2 bg-white text-primary-700 text-sm font-bold rounded-lg shadow-md hover:bg-primary-50 transition-all active:scale-95">
                    Input Transaksi Baru
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
