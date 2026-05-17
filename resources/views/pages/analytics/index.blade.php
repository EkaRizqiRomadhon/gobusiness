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

    <!-- ============================================= -->
    <!-- MONTHLY SALES CHART (NEW - Full Width)        -->
    <!-- ============================================= -->
    <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-surface-900">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 inline mr-1 text-primary-600"></i>
                    Grafik Penjualan Bulanan
                </h3>
                <p class="text-sm text-surface-500 mt-1">Omzet, keuntungan, dan produk terlaris per bulan</p>
            </div>
            <form method="GET" action="{{ route('analytics') }}" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg text-sm font-bold text-surface-700 outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @php
            $maxMonthlySales = $monthlyChart->max('total_sales') ?: 1;
        @endphp

        <!-- Chart Container -->
        <div class="relative">
            <!-- Bar Chart -->
            <div class="flex items-end gap-2 md:gap-3 px-2 bg-surface-50 p-6 rounded-xl" style="min-height: 350px;">
                @foreach($monthlyChart as $month)
                    @php
                        $h = ($month->total_sales / $maxMonthlySales) * 100;
                        $barHeight = max($h, 3);
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-end relative group hover:z-30" style="height: 300px;">
                        <!-- Tooltip -->
                        <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-surface-900 text-white text-[10px] px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 z-50 whitespace-nowrap pointer-events-none shadow-xl" style="min-width: 160px;">
                            <p class="font-bold text-primary-300 mb-1">{{ $month->month_name }} {{ $selectedYear }}</p>
                            <div class="space-y-0.5">
                                <p>💰 Omzet: Rp {{ number_format($month->total_sales, 0, ',', '.') }}</p>
                                <p>📊 Keuntungan: Rp {{ number_format($month->total_profit, 0, ',', '.') }}</p>
                                <p>🏷️ Transaksi: {{ $month->trx_count }}x</p>
                                <p>🏆 Terlaris: {{ $month->best_product }}</p>
                                @if($month->best_product_qty > 0)
                                    <p class="text-primary-300">&nbsp;&nbsp;&nbsp;({{ $month->best_product_qty }}x terjual)</p>
                                @endif
                            </div>
                        </div>

                        <!-- Bar -->
                        <div class="w-full max-w-[40px] rounded-t-lg transition-all duration-500 relative overflow-hidden cursor-pointer"
                             style="height: {{ $barHeight }}%; background: linear-gradient(to top, #7c3aed, #a78bfa);">
                            <div class="absolute inset-0 bg-white/0 group-hover:bg-white/20 transition-all duration-200"></div>
                        </div>

                        <!-- Month Label -->
                        <p class="text-[10px] md:text-xs text-surface-500 font-bold mt-2">{{ $month->month_name }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap items-center gap-4 mt-4 px-2">
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 rounded bg-primary-600"></div>
                    <span class="text-xs text-surface-500 font-medium">Omzet Penjualan</span>
                </div>
                <div class="text-xs text-surface-400">|</div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-surface-500">Hover pada bar untuk melihat detail <strong>keuntungan</strong> dan <strong>produk terlaris</strong></span>
                </div>
            </div>
        </div>

        <!-- Monthly Summary Table -->
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-surface-200">
                        <th class="pb-3 text-xs font-bold text-surface-400 uppercase tracking-wider">Bulan</th>
                        <th class="pb-3 text-xs font-bold text-surface-400 uppercase tracking-wider text-right">Omzet</th>
                        <th class="pb-3 text-xs font-bold text-surface-400 uppercase tracking-wider text-right">Keuntungan</th>
                        <th class="pb-3 text-xs font-bold text-surface-400 uppercase tracking-wider text-right">Trx</th>
                        <th class="pb-3 text-xs font-bold text-surface-400 uppercase tracking-wider">Produk Terlaris</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @foreach($monthlyChart as $month)
                    @if($month->total_sales > 0)
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="py-3 font-bold text-surface-700">{{ $month->month_name }} {{ $selectedYear }}</td>
                        <td class="py-3 text-right font-bold text-surface-900">Rp {{ number_format($month->total_sales, 0, ',', '.') }}</td>
                        <td class="py-3 text-right font-bold {{ $month->total_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($month->total_profit, 0, ',', '.') }}
                        </td>
                        <td class="py-3 text-right text-surface-600">{{ $month->trx_count }}x</td>
                        <td class="py-3">
                            @if($month->best_product !== '-')
                                <span class="inline-flex items-center px-2 py-0.5 bg-primary-50 text-primary-700 text-xs font-bold rounded-full">
                                    <i data-lucide="trophy" class="w-3 h-3 mr-1"></i>
                                    {{ $month->best_product }} ({{ $month->best_product_qty }}x)
                                </span>
                            @else
                                <span class="text-surface-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================= -->
    <!-- PIE CHART: Product Performance (NEW)          -->
    <!-- ============================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-surface-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-surface-900">
                        <i data-lucide="pie-chart" class="w-5 h-5 inline mr-1 text-primary-600"></i>
                        Kinerja Penjualan per Produk
                    </h3>
                    <p class="text-xs text-surface-500 mt-1">Distribusi omzet realtime berdasarkan produk</p>
                </div>
                <div class="text-xs font-bold text-surface-400 uppercase tracking-widest">Realtime</div>
            </div>

            @if($productPerformance->count() > 0 && $totalProductRevenue > 0)
            <!-- SVG Pie Chart -->
            <div class="flex justify-center mb-6">
                <div class="relative" style="width: 220px; height: 220px;">
                    <svg viewBox="0 0 42 42" class="w-full h-full transform -rotate-90">
                        @php
                            $colors = ['#7c3aed', '#a78bfa', '#c4b5fd', '#ddd6fe', '#ede9fe', '#f5f3ff', '#6d28d9', '#5b21b6', '#8b5cf6', '#4c1d95'];
                            $offset = 0;
                            $displayProducts = $productPerformance->take(10);
                        @endphp
                        @foreach($displayProducts as $index => $prod)
                            @php
                                $percent = $totalProductRevenue > 0 ? ($prod->total_revenue / $totalProductRevenue) * 100 : 0;
                                $dashArray = $percent . ' ' . (100 - $percent);
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <circle cx="21" cy="21" r="15.91549431"
                                    fill="transparent"
                                    stroke="{{ $color }}"
                                    stroke-width="5"
                                    stroke-dasharray="{{ $dashArray }}"
                                    stroke-dashoffset="-{{ $offset }}"
                                    class="transition-all duration-700 hover:opacity-80"
                                    style="cursor: pointer;">
                            </circle>
                            @php $offset += $percent; @endphp
                        @endforeach
                    </svg>
                    <!-- Center Text -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <p class="text-2xl font-bold text-surface-900">{{ $productPerformance->count() }}</p>
                        <p class="text-[10px] font-bold text-surface-400 uppercase">Produk</p>
                    </div>
                </div>
            </div>

            <!-- Legend List -->
            <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                @foreach($displayProducts as $index => $prod)
                    @php
                        $percent = $totalProductRevenue > 0 ? ($prod->total_revenue / $totalProductRevenue) * 100 : 0;
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full shrink-0" style="background-color: {{ $color }};"></div>
                            <span class="text-sm font-medium text-surface-700 truncate max-w-[180px]">{{ $prod->name }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-surface-500">{{ $prod->total_sold }}x</span>
                            <span class="text-sm font-bold text-surface-900 tabular-nums">{{ number_format($percent, 1) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-surface-400">
                <i data-lucide="pie-chart" class="w-16 h-16 mb-4 opacity-30"></i>
                <p class="text-sm">Belum ada data penjualan produk.</p>
            </div>
            @endif

            <div class="mt-6 p-4 bg-primary-50 rounded-xl border border-primary-100">
                <p class="text-xs text-primary-700 leading-relaxed">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1"></i>
                    Diagram ini menampilkan persentase kontribusi penjualan setiap produk secara realtime terhadap total omzet bisnis Anda.
                </p>
            </div>
        </div>

        <!-- Category Comparison (Existing - Bar Chart) -->
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
    </div>

    <!-- ============================================= -->
    <!-- Existing Charts Row                           -->
    <!-- ============================================= -->
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
                        <div class="relative group hover:z-20" style="width: 24px; height: {{ max($h, 5) }}%; background-color: #4f46e5; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-surface-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity z-30 whitespace-nowrap">
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

        <!-- Top Products -->
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
