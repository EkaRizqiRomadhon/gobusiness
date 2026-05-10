@extends('layouts.guest')

@section('content')
<div class="bg-white border border-surface-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-500">
    <div class="p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-surface-900">Buat Password Baru</h2>
            <p class="text-surface-500">Silakan masukkan password baru Anda.</p>
        </div>

        <form action="{{ route('simple.password.update') }}" method="POST" class="space-y-5">
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
                <label for="password" class="block text-sm font-medium text-surface-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required 
                        class="block w-full px-3 pr-10 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                        placeholder="Minimal 8 karakter">
                    <button type="button" id="togglePassword1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors focus:outline-none">
                        <i data-lucide="eye" class="w-5 h-5" id="eyeIcon1"></i>
                        <i data-lucide="eye-off" class="w-5 h-5 hidden" id="eyeOffIcon1"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-surface-700 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                        class="block w-full px-3 pr-10 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                        placeholder="Ulangi password baru">
                    <button type="button" id="togglePassword2" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors focus:outline-none">
                        <i data-lucide="eye" class="w-5 h-5" id="eyeIcon2"></i>
                        <i data-lucide="eye-off" class="w-5 h-5 hidden" id="eyeOffIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full premium-gradient text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center justify-center">
                Simpan Password Baru
                <i data-lucide="check-circle" class="ml-2 w-5 h-5"></i>
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-surface-500 hover:text-primary-600">
                Batal & Kembali ke Login
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupToggle(inputId, toggleBtnId, iconId, iconOffId) {
            const input = document.getElementById(inputId);
            const toggleBtn = document.getElementById(toggleBtnId);
            const icon = document.getElementById(iconId);
            const iconOff = document.getElementById(iconOffId);

            if (toggleBtn && input) {
                toggleBtn.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    if (type === 'text') {
                        icon.classList.add('hidden');
                        iconOff.classList.remove('hidden');
                    } else {
                        icon.classList.remove('hidden');
                        iconOff.classList.add('hidden');
                    }
                });
            }
        }

        setupToggle('password', 'togglePassword1', 'eyeIcon1', 'eyeOffIcon1');
        setupToggle('password_confirmation', 'togglePassword2', 'eyeIcon2', 'eyeOffIcon2');
    });
</script>
@endsection
