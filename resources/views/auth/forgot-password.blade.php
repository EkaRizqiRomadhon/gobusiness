@extends('layouts.guest')

@section('content')
<div class="bg-white border border-surface-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-500">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center mb-4">
                <i data-lucide="key-round" class="w-7 h-7 text-primary-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-surface-900">Lupa Password?</h2>
            <p class="text-surface-500 mt-1 text-sm">Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.</p>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start space-x-3">
                <i data-lucide="circle-check" class="w-5 h-5 text-green-600 shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-green-800">Email Terkirim!</p>
                    <p class="text-xs text-green-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start space-x-3">
                <i data-lucide="circle-x" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-surface-700 mb-1">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="block w-full pl-10 pr-3 py-2.5 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none {{ $errors->has('email') ? 'bg-red-50 border-red-400' : 'bg-white border-surface-300' }}"
                        placeholder="nama@email.com"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="w-full premium-gradient text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center justify-center space-x-2"
            >
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Kirim Link Reset Password</span>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-surface-50 border-t border-surface-100 p-4 flex justify-center">
        <a href="{{ route('login') }}" class="flex items-center space-x-2 text-sm text-surface-500 hover:text-primary-600 transition-colors font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Halaman Login</span>
        </a>
    </div>
</div>
@endsection
