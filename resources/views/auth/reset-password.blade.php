@extends('layouts.guest')

@section('content')
<div class="bg-white border border-surface-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in duration-500">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center mb-4">
                <i data-lucide="lock-keyhole" class="w-7 h-7 text-primary-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-surface-900">Buat Password Baru</h2>
            <p class="text-surface-500 mt-1 text-sm">Pastikan password baru Anda minimal 8 karakter dan mudah diingat.</p>
        </div>

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

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Hidden token & email -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email (readonly) -->
            <div>
                <label for="email" class="block text-sm font-medium text-surface-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $email) }}"
                        required
                        readonly
                        class="block w-full pl-10 pr-3 py-2.5 bg-surface-50 border border-surface-200 rounded-lg text-surface-500 cursor-not-allowed outline-none"
                    >
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-surface-700 mb-1">Password Baru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autofocus
                        class="block w-full pl-10 pr-10 py-2.5 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none {{ $errors->has('password') ? 'bg-red-50 border-red-400' : 'bg-white border-surface-300' }}"
                        placeholder="Minimal 8 karakter"
                    >
                    <button type="button" onclick="toggleVisibility('password', 'eye1', 'eyeoff1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors focus:outline-none">
                        <i data-lucide="eye" class="w-5 h-5" id="eye1"></i>
                        <i data-lucide="eye-off" class="w-5 h-5 hidden" id="eyeoff1"></i>
                    </button>
                </div>

                <!-- Password strength indicator -->
                <div class="mt-2 space-y-1">
                    <div class="flex space-x-1">
                        <div class="h-1 flex-1 rounded-full bg-surface-200 transition-colors" id="str1"></div>
                        <div class="h-1 flex-1 rounded-full bg-surface-200 transition-colors" id="str2"></div>
                        <div class="h-1 flex-1 rounded-full bg-surface-200 transition-colors" id="str3"></div>
                        <div class="h-1 flex-1 rounded-full bg-surface-200 transition-colors" id="str4"></div>
                    </div>
                    <p class="text-[10px] text-surface-400" id="strengthLabel">Masukkan password untuk melihat kekuatannya</p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-surface-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        class="block w-full pl-10 pr-10 py-2.5 bg-white border border-surface-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                        placeholder="Ulangi password baru"
                    >
                    <button type="button" onclick="toggleVisibility('password_confirmation', 'eye2', 'eyeoff2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-surface-400 hover:text-surface-600 transition-colors focus:outline-none">
                        <i data-lucide="eye" class="w-5 h-5" id="eye2"></i>
                        <i data-lucide="eye-off" class="w-5 h-5 hidden" id="eyeoff2"></i>
                    </button>
                </div>
                <!-- Match indicator -->
                <p class="text-[10px] mt-1 hidden" id="matchLabel"></p>
            </div>

            <button
                type="submit"
                class="w-full premium-gradient text-white font-semibold py-3 rounded-lg shadow-lg hover:shadow-primary-500/25 active:scale-[0.98] transition-all flex items-center justify-center space-x-2"
            >
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>Simpan Password Baru</span>
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-surface-50 border-t border-surface-100 p-4 flex justify-center">
        <div class="flex items-center text-xs text-surface-400 space-x-2">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>Password dienkripsi dengan algoritma bcrypt</span>
        </div>
    </div>
</div>

<script>
    function toggleVisibility(inputId, eyeId, eyeoffId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        const eyeoff = document.getElementById(eyeoffId);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        eye.classList.toggle('hidden', isPassword);
        eyeoff.classList.toggle('hidden', !isPassword);
    }

    // Password strength
    const pwInput = document.getElementById('password');
    const pwConfirm = document.getElementById('password_confirmation');
    const bars = [document.getElementById('str1'), document.getElementById('str2'), document.getElementById('str3'), document.getElementById('str4')];
    const strengthLabel = document.getElementById('strengthLabel');
    const matchLabel = document.getElementById('matchLabel');

    pwInput.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat'];

        bars.forEach((bar, i) => {
            bar.className = `h-1 flex-1 rounded-full transition-colors ${i < score ? colors[score - 1] : 'bg-surface-200'}`;
        });

        if (val.length === 0) {
            strengthLabel.textContent = 'Masukkan password untuk melihat kekuatannya';
            strengthLabel.className = 'text-[10px] text-surface-400';
        } else {
            strengthLabel.textContent = `Kekuatan: ${labels[score - 1] || 'Sangat Lemah'}`;
            strengthLabel.className = `text-[10px] ${score >= 3 ? 'text-green-600' : score === 2 ? 'text-yellow-600' : 'text-red-600'}`;
        }

        checkMatch();
    });

    pwConfirm.addEventListener('input', checkMatch);

    function checkMatch() {
        if (pwConfirm.value.length === 0) {
            matchLabel.classList.add('hidden');
            return;
        }
        matchLabel.classList.remove('hidden');
        if (pwInput.value === pwConfirm.value) {
            matchLabel.textContent = '✓ Password cocok';
            matchLabel.className = 'text-[10px] mt-1 text-green-600';
            pwConfirm.classList.remove('border-red-400');
            pwConfirm.classList.add('border-green-400');
        } else {
            matchLabel.textContent = '✗ Password tidak cocok';
            matchLabel.className = 'text-[10px] mt-1 text-red-600';
            pwConfirm.classList.add('border-red-400');
            pwConfirm.classList.remove('border-green-400');
        }
    }
</script>
@endsection
