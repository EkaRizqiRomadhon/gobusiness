@extends('layouts.guest')

@section('content')
<div class="bg-white border border-surface-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-500">
    <div class="p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-surface-900">Selamat Datang Kembali</h2>
            <p class="text-surface-500">Silakan masuk ke akun GO Business Anda</p>
        </div>

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center space-x-2">
                <i data-lucide="circle-check" class="w-4 h-4 text-green-600 shrink-0"></i>
                <p class="text-xs text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

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
                <label for="email" class="block text-sm font-medium text-surface-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input type="email" name="email" id="email" required 
                        class="block w-full pl-10 pr-3 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-surface-700">Password</label>
                    <a href="{{ route('simple.password.request') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lupa password?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input type="password" name="password" id="password" required 
                        class="block w-full pl-10 pr-10 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                        placeholder="••••••••">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors focus:outline-none">
                        <i data-lucide="eye" class="w-5 h-5" id="eyeIcon"></i>
                        <i data-lucide="eye-off" class="w-5 h-5 hidden" id="eyeOffIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" class="w-4 h-4 text-primary-600 border-surface-300 rounded focus:ring-primary-500">
                <label for="remember" class="ml-2 block text-sm text-surface-600">Ingat saya</label>
            </div>

            <button type="submit" class="w-full premium-gradient text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center justify-center">
                Masuk
                <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-surface-500">
                Belum punya akun? 
                <a href="/register" class="font-semibold text-primary-600 hover:text-primary-700">Daftar sekarang</a>
            </p>
        </div>
    </div>
    
    <!-- Footer decoration -->
    <div class="bg-surface-50 border-t border-surface-100 p-4 flex justify-center space-x-6">
        <div class="flex items-center text-xs text-surface-400">
            <i data-lucide="shield-check" class="w-4 h-4 mr-1"></i>
            Aman & Terenkripsi
        </div>
        <div class="flex items-center text-xs text-surface-400">
            <i data-lucide="zap" class="w-4 h-4 mr-1"></i>
            Cepat & Responsif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
    });
</script>
@endsection
