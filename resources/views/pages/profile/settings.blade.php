@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-surface-900">Keamanan & Privasi</h2>
        <p class="text-surface-500 text-sm mt-1">Kelola data dan keberadaan akun Anda di sistem.</p>
    </div>

    <!-- Simple Settings Card -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <h4 class="font-bold text-surface-900 text-lg mb-1">Hapus Akun Permanen</h4>
                <p class="text-sm text-surface-500 leading-relaxed max-w-md">
                    Setelah dihapus, data bisnis, stok produk, dan riwayat transaksi Anda tidak dapat dipulihkan kembali.
                </p>
            </div>
            <div>
                <button onclick="toggleModal('deleteAccountModal')" 
                    style="background-color: #dc2626 !important; color: white !important;"
                    class="px-8 py-3.5 text-white font-bold rounded-xl transition-all hover:bg-red-700 active:scale-95 flex items-center justify-center min-w-[180px]">
                    <i data-lucide="trash-2" class="w-5 h-5 mr-2"></i>
                    Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteAccountModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm" onclick="toggleModal('deleteAccountModal')"></div>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-8">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold text-surface-900 text-center mb-2">Hapus Akun?</h3>
                <p class="text-surface-500 text-center text-sm leading-relaxed mb-8">
                    Tindakan ini permanen. Semua data Anda akan dihapus dari server kami.
                </p>
                
                <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: #dc2626 !important;" class="w-full py-3.5 text-white font-bold rounded-xl hover:bg-red-700 transition-all active:scale-95">
                        Ya, Hapus Permanen
                    </button>
                    <button type="button" onclick="toggleModal('deleteAccountModal')" class="w-full py-3.5 bg-surface-100 text-surface-700 font-bold rounded-xl hover:bg-surface-200 transition-all">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }
</script>
@endsection
