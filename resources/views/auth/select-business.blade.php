@extends('layouts.guest')

@section('content-width', 'max-w-4xl')

@section('content')
<div class="py-4">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 premium-gradient rounded-2xl mb-4 shadow-lg">
            <i data-lucide="building-2" class="w-8 h-8 text-white"></i>
        </div>
        <h1 class="text-3xl font-bold text-surface-900 mb-2">Pilih Jenis Usaha Anda</h1>
        <p class="text-surface-500 max-w-lg mx-auto">Pilih jenis usaha yang sesuai. Kategori produk akan otomatis disesuaikan untuk memudahkan pengelolaan bisnis Anda.</p>
    </div>

    @if ($errors->any())
        <div class="p-3 mb-6 bg-red-50 border border-red-200 rounded-lg max-w-md mx-auto">
            <ul class="list-disc list-inside text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('business.select.store') }}" method="POST" id="businessForm">
        @csrf
        <input type="hidden" name="business_type" id="selected_business_type">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($businessTypes as $type => $data)
            <div class="business-card bg-white border-2 border-surface-200 rounded-2xl overflow-hidden cursor-pointer hover:border-primary-400 hover:shadow-lg transition-all duration-300" 
                 data-type="{{ $type }}">
                
                {{-- Card Header - clickable to select --}}
                <div class="p-5 pb-3" onclick="selectBusiness('{{ $type }}')">
                    <div class="flex items-start justify-between mb-3">
                        <div class="card-icon p-3 bg-primary-50 rounded-xl transition-colors duration-300">
                            <i data-lucide="{{ $data['icon'] }}" class="w-6 h-6 text-primary-600"></i>
                        </div>
                        <div class="radio-indicator w-6 h-6 border-2 border-surface-300 rounded-full flex items-center justify-center transition-colors duration-300">
                            <div class="dot w-3 h-3 bg-primary-600 rounded-full scale-0 transition-transform duration-200"></div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-surface-900">{{ $type }}</h3>
                    <p class="text-xs text-surface-400 mt-1">{{ count($data['categories']) }} kategori produk</p>
                </div>

                {{-- Expand toggle --}}
                <button type="button" class="expand-btn w-full px-5 py-2.5 flex items-center justify-between text-xs font-semibold text-primary-600 hover:bg-primary-50 transition-colors border-t border-surface-100"
                        onclick="toggleCategories(this)">
                    <span>Lihat Kategori</span>
                    <span class="chevron-icon"></span>
                </button>

                {{-- Categories list (hidden by default) --}}
                <div class="categories-list hidden">
                    <div class="px-5 pb-4 pt-1 space-y-1.5">
                        @foreach($data['categories'] as $category)
                        <div class="flex items-center gap-2 py-1.5 px-3 bg-surface-50 rounded-lg">
                            <i data-lucide="tag" class="w-3.5 h-3.5 text-primary-500 flex-shrink-0"></i>
                            <span class="text-sm text-surface-700">{{ $category }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">
            <button type="submit" id="submitBtn" disabled
                    class="submit-btn px-12 py-4 bg-surface-200 text-surface-400 font-bold rounded-xl cursor-not-allowed transition-all duration-300 flex items-center gap-3 text-lg">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Konfirmasi Pilihan
            </button>
        </div>
    </form>
</div>

<style>
    .business-card.selected {
        border-color: var(--color-primary-500, #0ea5e9);
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.03) 0%, rgba(37, 99, 235, 0.05) 100%);
        box-shadow: 0 10px 25px -5px rgba(14, 165, 233, 0.15);
    }
    .business-card.selected .card-icon {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    }
    .business-card.selected .card-icon svg,
    .business-card.selected .card-icon i {
        color: white;
    }
    .business-card.selected .radio-indicator {
        border-color: var(--color-primary-500, #0ea5e9);
    }
    .business-card.selected .dot {
        transform: scale(1);
    }
    .chevron-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-right: 2px solid currentColor;
        border-bottom: 2px solid currentColor;
        transform: rotate(45deg);
        transition: transform 0.3s ease;
        margin-top: -4px;
    }
    .chevron-icon.rotated {
        transform: rotate(-135deg);
        margin-top: 2px;
    }
    .submit-btn.active {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        color: white;
        cursor: pointer;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3);
    }
    .submit-btn.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.4);
    }
    .submit-btn.active:active {
        transform: scale(0.98);
    }
</style>

<script>
    function selectBusiness(type) {
        document.getElementById('selected_business_type').value = type;
        
        document.querySelectorAll('.business-card').forEach(card => {
            card.classList.remove('selected');
            if (card.dataset.type === type) {
                card.classList.add('selected');
            }
        });
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        submitBtn.classList.add('active');
    }

    function toggleCategories(btn) {
        const card = btn.closest('.business-card');
        
        // Tutup kategori dari card lain yang sedang terbuka
        document.querySelectorAll('.business-card').forEach(otherCard => {
            if (otherCard !== card) {
                const otherList = otherCard.querySelector('.categories-list');
                const otherIcon = otherCard.querySelector('.chevron-icon');
                const otherSpan = otherCard.querySelector('.expand-btn span');
                
                if (otherList && !otherList.classList.contains('hidden')) {
                    otherList.classList.add('hidden');
                    if (otherIcon) otherIcon.classList.remove('rotated');
                    if (otherSpan) otherSpan.textContent = 'Lihat Kategori';
                }
            }
        });

        const list = card.querySelector('.categories-list');
        const icon = btn.querySelector('.chevron-icon');
        
        list.classList.toggle('hidden');
        icon.classList.toggle('rotated');
        
        const span = btn.querySelector('span');
        span.textContent = list.classList.contains('hidden') ? 'Lihat Kategori' : 'Sembunyikan';
    }
</script>
@endsection
