@extends('layouts.guest')

@section('content')
<div class="bg-white border border-surface-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-500">
    <div class="p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-surface-900">Verifikasi Akun</h2>
            <p class="text-surface-500">Masukkan Email dan Nama Bisnis Anda untuk mereset password tanpa email konfirmasi.</p>
        </div>

        <form action="{{ route('simple.password.verify') }}" method="POST" class="space-y-5">
            @csrf
            
            @if ($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-xs text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-surface-700 mb-1">Email Terdaftar</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    class="block w-full px-3 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                    placeholder="nama@email.com">
            </div>

            <div>
                <label for="business_name" class="block text-sm font-medium text-surface-700 mb-1">Nama Bisnis</label>
                <input type="text" name="business_name" id="business_name" required value="{{ old('business_name') }}"
                    class="block w-full px-3 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                    placeholder="Contoh: Toko Berkah">
            </div>

            <button type="submit" class="w-full premium-gradient text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center justify-center">
                Lanjut Ganti Password
                <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-surface-500 hover:text-primary-600">
                &larr; Kembali ke halaman Login
            </a>
        </div>
    </div>
</div>
@endsection
