@extends('layouts.app')

@section('title', 'Masa Expired Produk')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">Masa Expired Produk</h2>
            <p class="text-surface-500">Pantau tanggal kedaluwarsa produk Anda untuk mencegah kerugian.</p>
        </div>
        <a href="{{ route('stock.index') }}" class="flex items-center px-5 py-2.5 bg-surface-100 text-surface-700 font-bold rounded-xl hover:bg-surface-200 active:scale-95 transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Stok
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Tracked -->
        <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Dipantau</p>
                    <p class="text-2xl font-bold text-surface-900 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-11 h-11 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="eye" class="w-5 h-5 text-primary-600"></i>
                </div>
            </div>
        </div>
        <!-- Expired -->
        <div class="bg-white p-5 rounded-2xl border border-red-200 shadow-sm {{ $stats['expired'] > 0 ? 'ring-2 ring-red-200' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Expired</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['expired'] }}</p>
                </div>
                <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
            </div>
        </div>
        <!-- Critical (< 7 days) -->
        <div class="bg-white p-5 rounded-2xl border border-orange-200 shadow-sm {{ $stats['critical'] > 0 ? 'ring-2 ring-orange-200' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest">Kritis (&lt;7 Hari)</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $stats['critical'] }}</p>
                </div>
                <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-600"></i>
                </div>
            </div>
        </div>
        <!-- Warning (< 30 days) -->
        <div class="bg-white p-5 rounded-2xl border border-yellow-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-yellow-600 uppercase tracking-widest">Peringatan (&lt;30 Hari)</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['warning'] }}</p>
                </div>
                <div class="w-11 h-11 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    @if($stats['expired'] > 0 || $stats['critical'] > 0)
    <!-- Urgent Alert -->
    <div class="p-5 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-2xl flex items-start gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="siren" class="w-6 h-6 text-red-600"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-red-800">Perhatian!</h4>
            <p class="text-sm text-red-700 mt-1">
                Terdapat <strong>{{ $stats['expired'] }} produk expired</strong> dan <strong>{{ $stats['critical'] }} produk kritis</strong> yang akan segera kedaluwarsa dalam 7 hari.
                Segera ambil tindakan untuk menghindari kerugian.
            </p>
        </div>
    </div>
    @endif

    <!-- Expired Products Section -->
    @if($expired->count() > 0)
    <div class="bg-white rounded-2xl border border-red-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-red-50 border-b border-red-200 flex items-center">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                <i data-lucide="x-circle" class="w-4 h-4 text-red-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-800">Produk Sudah Expired</h3>
                <p class="text-xs text-red-600">Produk ini sudah melewati tanggal kedaluwarsa</p>
            </div>
        </div>
        <div class="divide-y divide-red-100">
            @foreach($expired as $product)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-red-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-red-200 grayscale opacity-70" alt="{{ $product->name }}">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center text-red-300">
                            <i data-lucide="image" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-surface-900">{{ $product->name }}</p>
                        <p class="text-xs text-surface-500">{{ $product->category->name ?? '-' }} · Stok: {{ $product->stock }} unit</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">
                        <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                        EXPIRED
                    </span>
                    <p class="text-xs text-red-500 mt-1">{{ $product->expired_at->format('d M Y') }} · {{ $product->expired_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Critical Products Section -->
    @if($critical->count() > 0)
    <div class="bg-white rounded-2xl border border-orange-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-orange-50 border-b border-orange-200 flex items-center">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-orange-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-orange-800">Kritis — Expired dalam &lt; 7 Hari</h3>
                <p class="text-xs text-orange-600">Segera jual atau tarik produk ini dari peredaran</p>
            </div>
        </div>
        <div class="divide-y divide-orange-100">
            @foreach($critical as $product)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-orange-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-orange-200" alt="{{ $product->name }}">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center text-orange-300">
                            <i data-lucide="image" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-surface-900">{{ $product->name }}</p>
                        <p class="text-xs text-surface-500">{{ $product->category->name ?? '-' }} · Stok: {{ $product->stock }} unit</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-orange-100 text-orange-700 rounded-full animate-pulse">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        {{ $product->expired_at->diffInDays(now()) }} hari lagi
                    </span>
                    <p class="text-xs text-orange-500 mt-1">{{ $product->expired_at->format('d M Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Warning Products Section -->
    @if($warning->count() > 0)
    <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200 flex items-center">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                <i data-lucide="clock" class="w-4 h-4 text-yellow-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-yellow-800">Peringatan — Expired dalam &lt; 30 Hari</h3>
                <p class="text-xs text-yellow-600">Pertimbangkan promosi untuk produk-produk berikut</p>
            </div>
        </div>
        <div class="divide-y divide-yellow-100">
            @foreach($warning as $product)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-yellow-50/50 transition-colors">
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-yellow-200" alt="{{ $product->name }}">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-300">
                            <i data-lucide="image" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-surface-900">{{ $product->name }}</p>
                        <p class="text-xs text-surface-500">{{ $product->category->name ?? '-' }} · Stok: {{ $product->stock }} unit</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded-full">
                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                        {{ $product->expired_at->diffInDays(now()) }} hari lagi
                    </span>
                    <p class="text-xs text-yellow-500 mt-1">{{ $product->expired_at->format('d M Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Safe Products Section -->
    @if($safe->count() > 0)
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-green-50 border-b border-green-200 flex items-center">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-green-800">Aman — Expired &gt; 30 Hari</h3>
                <p class="text-xs text-green-600">Produk masih dalam masa simpan yang aman</p>
            </div>
        </div>
        <div class="divide-y divide-surface-100">
            @foreach($safe as $product)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-surface-50 transition-colors">
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-surface-200" alt="{{ $product->name }}">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-surface-100 flex items-center justify-center text-surface-400">
                            <i data-lucide="image" class="w-6 h-6"></i>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-surface-900">{{ $product->name }}</p>
                        <p class="text-xs text-surface-500">{{ $product->category->name ?? '-' }} · Stok: {{ $product->stock }} unit</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">
                        <i data-lucide="shield-check" class="w-3 h-3 mr-1"></i>
                        {{ $product->expired_at->diffInDays(now()) }} hari lagi
                    </span>
                    <p class="text-xs text-surface-400 mt-1">{{ $product->expired_at->format('d M Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($stats['total'] === 0)
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-surface-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i data-lucide="calendar-off" class="w-10 h-10 text-surface-300"></i>
        </div>
        <h3 class="text-lg font-bold text-surface-900 mb-2">Belum Ada Produk dengan Tanggal Expired</h3>
        <p class="text-surface-500 mb-6">Anda bisa menambahkan tanggal kedaluwarsa saat menambahkan atau mengedit produk di halaman Stok Produk.</p>
        <a href="{{ route('stock.index') }}" class="inline-flex items-center px-6 py-3 premium-gradient text-white font-bold rounded-xl shadow-lg hover:shadow-primary-500/25 active:scale-95 transition-all">
            <i data-lucide="package-plus" class="w-5 h-5 mr-2"></i>
            Kelola Stok Produk
        </a>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
