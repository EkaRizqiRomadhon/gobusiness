@extends('layouts.app')

@section('title', 'Transaksi Kasir')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 h-[calc(100vh-160px)] animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Left: Product Selection -->
    <div class="flex-1 flex flex-col min-w-0">
        <div class="mb-6 space-y-4 animate-in slide-in-from-top-2 duration-500">
            <!-- Search Bar -->
            <div class="relative w-full max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-surface-400">
                    <i data-lucide="search" class="w-4.5 h-4.5"></i>
                </div>
                <input type="text" id="productSearch" onkeyup="filterProducts()" 
                    class="block w-full pl-12 pr-4 py-3.5 bg-white border border-surface-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all placeholder:text-surface-400" 
                    placeholder="Cari produk">
            </div>

            <!-- Categories -->
            <div class="flex items-center space-x-2 overflow-x-auto no-scrollbar py-1">
                <button onclick="filterCategory('all')" 
                    class="category-btn px-6 py-2 text-sm font-bold bg-primary-50 text-primary-600 rounded-xl border border-primary-100 shadow-sm transition-all whitespace-nowrap active:scale-95" 
                    data-category="all">Semua</button>
                @foreach($products->pluck('category.name')->unique() as $catName)
                    @if($catName)
                        <button onclick="filterCategory('{{ $catName }}')" 
                            class="category-btn px-6 py-2 text-sm font-bold text-surface-500 bg-white border border-surface-200 rounded-xl shadow-sm hover:bg-surface-50 transition-all whitespace-nowrap active:scale-95" 
                            data-category="{{ $catName }}">{{ $catName }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productGrid">
                @forelse($products as $product)
                <div class="product-card bg-white p-4 rounded-2xl border border-surface-200 shadow-sm hover:shadow-md hover:border-primary-200 transition-all cursor-pointer group active:scale-95" 
                     data-name="{{ strtolower($product->name) }}" 
                     data-category="{{ $product->category->name ?? '' }}"
                     onclick="addToCart({{ json_encode($product) }})">
                    <div class="aspect-square bg-surface-50 rounded-xl mb-4 flex items-center justify-center text-primary-600 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <i data-lucide="package" class="w-10 h-10 opacity-20 group-hover:scale-110 transition-transform"></i>
                        @endif
                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-lg text-[10px] font-bold shadow-sm">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-surface-900 leading-tight mb-1">{{ $product->name }}</h4>
                    <p class="text-primary-600 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-surface-400">Belum ada produk tersedia. Tambahkan produk di menu Stok.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Cart & Summary -->
    <div class="w-full lg:w-96 flex flex-col bg-white rounded-3xl border border-surface-200 shadow-xl overflow-hidden">
        <div class="p-6 border-b border-surface-100 flex items-center justify-between">
            <div class="flex items-center">
                <i data-lucide="shopping-cart" class="w-5 h-5 text-primary-600 mr-2"></i>
                <h3 class="font-bold text-surface-900">Keranjang</h3>
            </div>
            <button onclick="clearCart()" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">Kosongkan</button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" id="cartItems">
            <!-- Empty State -->
            <div id="emptyCart" class="h-full flex flex-col items-center justify-center text-center py-10">
                <div class="w-16 h-16 bg-surface-50 rounded-full flex items-center justify-center text-surface-300 mb-4">
                    <i data-lucide="shopping-basket" class="w-8 h-8"></i>
                </div>
                <p class="text-sm text-surface-400 font-medium">Keranjang masih kosong</p>
            </div>
        </div>

        <div class="p-6 bg-surface-50 border-t border-surface-100 space-y-4">
            <!-- Payment Method Selection -->
            <div class="space-y-2">
                <p class="text-xs font-bold text-surface-500 uppercase tracking-wider">Metode Pembayaran</p>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center p-3 border border-surface-200 rounded-xl bg-white cursor-pointer hover:border-primary-300 transition-all group">
                        <input type="radio" name="payment_method_select" value="cash" checked onchange="togglePaymentMethod('cash')" class="absolute opacity-0">
                        <div class="flex flex-col items-center space-y-1">
                            <i data-lucide="banknote" class="w-5 h-5 text-surface-400 group-has-checked:text-primary-600"></i>
                            <span class="text-xs font-bold text-surface-600 group-has-checked:text-primary-950">Tunai</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent group-has-checked:border-primary-500 rounded-xl pointer-events-none"></div>
                    </label>
                    <label class="relative flex items-center justify-center p-3 border border-surface-200 rounded-xl bg-white cursor-pointer hover:border-primary-300 transition-all group">
                        <input type="radio" name="payment_method_select" value="qris" onchange="togglePaymentMethod('qris')" class="absolute opacity-0">
                        <div class="flex flex-col items-center space-y-1">
                            <i data-lucide="qr-code" class="w-5 h-5 text-surface-400 group-has-checked:text-primary-600"></i>
                            <span class="text-xs font-bold text-surface-600 group-has-checked:text-primary-950">QRIS</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent group-has-checked:border-primary-500 rounded-xl pointer-events-none"></div>
                    </label>
                </div>
            </div>

            <!-- QRIS Reference/View -->
            <div id="qrisSection" class="hidden animate-in fade-in zoom-in duration-300 space-y-3">
                <button type="button" onclick="showQrisModal()" class="w-full flex items-center justify-center p-2 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100 hover:bg-blue-100 transition-colors">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Tampilkan QRIS Toko
                </button>
                <input type="text" id="refInput" placeholder="Masukkan No. Referensi (Opsional)" 
                    class="block w-full px-3 py-2 bg-white border border-surface-200 rounded-lg text-xs outline-none focus:ring-2 focus:ring-primary-500 transition-all">
            </div>

            <div class="flex justify-between text-lg font-bold text-surface-900 pt-2 border-t border-surface-200">
                <span>Total Bayar</span>
                <span id="totalLabel">Rp 0</span>
            </div>
            
            <form id="checkoutForm" action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div id="hiddenFields"></div>
                <input type="hidden" name="tax" value="0">
                <input type="hidden" name="payment_method" id="finalPaymentMethod" value="cash">
                <input type="hidden" name="reference_number" id="finalRefNumber" value="">
                
                <button type="submit" id="payButton" disabled class="w-full mt-2 py-4 bg-primary-600 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 hover:bg-primary-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100">
                    Selesaikan Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<!-- QRIS Modal -->
<div id="qrisModal" class="fixed inset-0 z-60 items-center justify-center p-4 bg-surface-900/80 hidden animate-in fade-in duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full relative animate-in zoom-in-95 duration-300">
        <button onclick="hideQrisModal()" class="absolute top-4 right-4 p-2 hover:bg-surface-100 rounded-full">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <div class="text-center">
            <h4 class="text-xl font-bold text-surface-900 mb-2">QRIS Merchant</h4>
            <p class="text-sm text-surface-500 mb-6">{{ Auth::user()->name }}</p>
            
            @if(Auth::user()->qris_path)
                <img src="{{ asset('storage/' . Auth::user()->qris_path) }}" alt="QRIS" class="w-full aspect-square object-contain bg-surface-50 rounded-2xl p-4 border border-surface-100">
            @else
                <div class="w-full aspect-square flex flex-col items-center justify-center bg-surface-50 rounded-2xl border-2 border-dashed border-surface-200 text-surface-400">
                    <i data-lucide="image-off" class="w-12 h-12 mb-3"></i>
                    <p class="text-xs font-bold uppercase">QRIS Belum Diupload</p>
                    <a href="{{ route('profile.index') }}" class="mt-2 text-xs text-primary-600 font-bold hover:underline">Upload Sekarang</a>
                </div>
            @endif
            
            <p class="mt-6 text-[10px] text-surface-400 font-bold uppercase tracking-widest">Silakan scan untuk membayar</p>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    let cart = [];

    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);
        if (existing) {
            if (existing.quantity < product.stock) {
                existing.quantity++;
            } else {
                alert('Stok tidak mencukupi!');
            }
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                quantity: 1
            });
        }
        renderCart();
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            const newQty = item.quantity + delta;
            if (newQty > 0 && newQty <= item.stock) {
                item.quantity = newQty;
            } else if (newQty === 0) {
                cart = cart.filter(i => i.id !== id);
            } else if (newQty > item.stock) {
                alert('Stok tidak mencukupi!');
            }
        }
        renderCart();
    }

    function removeItem(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    function clearCart() {
        if (confirm('Kosongkan keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const emptyState = document.getElementById('emptyCart');
        const hiddenFields = document.getElementById('hiddenFields');
        
        if (cart.length === 0) {
            container.innerHTML = '';
            container.appendChild(emptyState);
            document.getElementById('payButton').disabled = true;
            document.getElementById('totalLabel').innerText = 'Rp 0';
            hiddenFields.innerHTML = '';
            return; // Exit early if cart is empty
        }

        document.getElementById('payButton').disabled = false;
        container.innerHTML = cart.map(item => `
            <div class="flex items-center justify-between group animate-in slide-in-from-right-4 duration-300">
                <div class="flex-1 min-w-0 pr-4">
                    <p class="text-sm font-bold text-surface-900 truncate">${item.name}</p>
                    <p class="text-xs text-primary-600 font-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                </div>
                <div class="flex items-center bg-surface-50 rounded-xl p-1 border border-surface-200">
                    <button onclick="changeQty(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center hover:bg-white hover:shadow-sm rounded-lg transition-all text-surface-500">
                        <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                    </button>
                    <span class="w-8 text-center text-xs font-bold text-surface-900">${item.quantity}</span>
                    <button onclick="changeQty(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center hover:bg-white hover:shadow-sm rounded-lg transition-all text-surface-500">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        `).join('');
        
        // Re-render icons
        lucide.createIcons();

        // Update Summary
        const total = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
        document.getElementById('totalLabel').innerText = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;

        // Populate hidden fields for form submission
        hiddenFields.innerHTML = cart.map((item, index) => `
            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
        `).join('');
    }

    function filterProducts() {
        const query = document.getElementById('productSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        
        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            if (name.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterCategory(category) {
        const cards = document.querySelectorAll('.product-card');
        const buttons = document.querySelectorAll('.category-btn');
        
        buttons.forEach(btn => {
            if (btn.getAttribute('data-category') === category) {
                btn.classList.add('bg-primary-50', 'text-primary-600', 'border-primary-100');
                btn.classList.remove('text-surface-500', 'bg-white', 'border-surface-200');
            } else {
                btn.classList.remove('bg-primary-50', 'text-primary-600', 'border-primary-100');
                btn.classList.add('text-surface-500', 'bg-white', 'border-surface-200');
            }
        });

        cards.forEach(card => {
            if (category === 'all' || card.getAttribute('data-category') === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function togglePaymentMethod(method) {
        const qrisSection = document.getElementById('qrisSection');
        const finalPaymentMethod = document.getElementById('finalPaymentMethod');
        
        finalPaymentMethod.value = method;
        
        if (method === 'qris') {
            qrisSection.classList.remove('hidden');
        } else {
            qrisSection.classList.add('hidden');
            document.getElementById('finalRefNumber').value = '';
            document.getElementById('refInput').value = '';
        }
    }

    function showQrisModal() {
        document.getElementById('qrisModal').classList.replace('hidden', 'flex');
    }

    function hideQrisModal() {
        document.getElementById('qrisModal').classList.replace('flex', 'hidden');
    }

    document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
        document.getElementById('finalRefNumber').value = document.getElementById('refInput').value;
    });

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
