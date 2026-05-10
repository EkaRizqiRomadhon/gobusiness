@extends('layouts.app')

@section('title', 'Profil & Pengaturan Bisnis')

@section('content')
<div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Breadcrumb -->
    <nav class="flex text-sm text-surface-500 mb-4">
        <a href="/dashboard" class="hover:text-primary-600">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-surface-900 font-medium">Profil</span>
    </nav>

    @if (session('success'))
        <div class="alert-auto-hide p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-surface-200">
            <h3 class="text-lg font-bold text-surface-900">Informasi Bisnis</h3>
            <p class="text-sm text-surface-500">Kelola informasi dasar dan metode pembayaran QRIS Anda.</p>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Business Name -->
            <div>
                <label for="business_name" class="block text-sm font-medium text-surface-700 mb-1">Nama Usaha / Toko</label>
                <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $user->business_name) }}" required 
                    class="block w-full px-4 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                @error('business_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Owner Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-surface-700 mb-1">Nama Owner</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                    class="block w-full px-4 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email (Read Only) -->
            <div>
                <label class="block text-sm font-medium text-surface-700 mb-1">Email Akun</label>
                <input type="text" value="{{ $user->email }}" readonly 
                    class="block w-full px-4 py-2.5 bg-surface-50 border border-surface-200 rounded-lg text-surface-500 cursor-not-allowed">
                <p class="mt-1 text-[10px] text-surface-400 font-medium uppercase tracking-wider">Email tidak dapat diubah</p>
            </div>

            <hr class="border-surface-100">

            <!-- QRIS Upload -->
            <div>
                <label class="block text-sm font-medium text-surface-700 mb-1">QRIS Merchant</label>
                <p class="text-xs text-surface-500 mb-4">Gambar ini akan ditampilkan saat kasir memilih metode pembayaran QRIS.</p>
                
                <div class="flex items-start space-x-6">
                    <div class="shrink-0">
                        @if($user->qris_path)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $user->qris_path) }}" alt="QRIS" class="w-32 h-32 object-contain border border-surface-200 rounded-xl p-2 bg-surface-50">
                                <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-[10px] text-white font-bold uppercase tracking-widest">Ganti Gambar</span>
                                </div>
                            </div>
                        @else
                            <div class="w-32 h-32 bg-surface-50 border-2 border-dashed border-surface-200 rounded-xl flex flex-col items-center justify-center text-surface-400">
                                <i data-lucide="qr-code" class="w-8 h-8 mb-2"></i>
                                <span class="text-[10px] font-bold uppercase">Belum Ada</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <input type="file" name="qris_image" id="qris_image" class="block w-full text-sm text-surface-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-xs file:font-bold
                            file:bg-primary-50 file:text-primary-700
                            hover:file:bg-primary-100
                            cursor-pointer">
                        <p class="mt-2 text-xs text-surface-400">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                    </div>
                </div>
                @error('qris_image')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="premium-gradient text-white font-bold px-8 py-3 rounded-xl shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
