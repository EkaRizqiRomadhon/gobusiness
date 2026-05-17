@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">Inventaris Produk</h2>
            <p class="text-surface-500">Kelola stok barang dan unggah foto produk Anda.</p>
        </div>
        <button onclick="toggleModal('addProductModal')" class="flex items-center px-5 py-2.5 premium-gradient text-white font-bold rounded-xl shadow-lg hover:shadow-primary-500/25 active:scale-95 transition-all">
            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
            Tambah Produk
        </button>
    </div>

    @if(session('success'))
        <div class="alert-auto-hide p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl space-y-1">
            <div class="flex items-center font-bold mb-1">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                Ada kesalahan input:
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Product Table -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 border-b border-surface-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Nama & SKU</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Expired</th>
                        <th class="px-6 py-4 text-xs font-bold text-surface-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" class="w-12 h-12 rounded-lg object-cover border border-surface-200 shadow-sm" alt="{{ $product->name }}">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-surface-100 flex items-center justify-center text-surface-400">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-surface-900">{{ $product->name }}</p>
                            <p class="text-xs text-surface-500">SKU: {{ $product->sku ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium bg-surface-100 text-surface-600 rounded-full">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-surface-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if($product->discount > 0 || $product->tax > 0)
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        @if($product->discount > 0)
                                            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-1 rounded">-{{ number_format($product->discount, 0, ',', '.') }}</span>
                                        @endif
                                        @if($product->tax > 0)
                                            <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-1 rounded">+{{ $product->tax }}%</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm {{ $product->stock <= $product->min_stock_threshold ? 'text-red-600 font-bold' : 'text-surface-600' }}">
                                {{ $product->stock }} unit
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->expired_at)
                                @php $status = $product->expiryStatus(); @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full
                                    {{ $status === 'expired' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $status === 'critical' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $status === 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $status === 'safe' ? 'bg-green-100 text-green-700' : '' }}
                                ">
                                    @if($status === 'expired')
                                        <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                                    @elseif($status === 'critical')
                                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    @endif
                                    {{ $product->expired_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-xs text-surface-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <button onclick="openEditModal({{ json_encode($product) }})" class="text-surface-400 hover:text-primary-600 transition-colors"><i data-lucide="edit-2" class="w-4 h-4"></i></button>
                                <form action="{{ route('stock.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-surface-400 hover:text-red-600 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-surface-400">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div id="addProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm" onclick="toggleModal('addProductModal')"></div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 animate-in zoom-in duration-300">
            <h3 class="text-xl font-bold text-surface-900 mb-6">Tambah Produk</h3>
            <form action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">Foto Produk</label>
                        <input type="file" name="image" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">SKU</label>
                        <input type="text" name="sku" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Harga</label>
                        <input type="number" name="price" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Stok</label>
                        <input type="number" name="stock" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Diskon (Rp)</label>
                        <input type="number" name="discount" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Pajak (%)</label>
                        <input type="number" step="0.01" name="tax" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none" placeholder="0">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">
                            <i data-lucide="calendar-clock" class="w-4 h-4 inline mr-1"></i>
                            Tanggal Expired <span class="text-surface-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="date" name="expired_at" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none focus:ring-2 focus:ring-primary-500">
                        <p class="text-xs text-surface-400 mt-1">Kosongkan jika produk tidak memiliki tanggal kedaluwarsa.</p>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="toggleModal('addProductModal')" class="flex-1 px-4 py-2.5 bg-surface-100 text-surface-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 premium-gradient text-white font-bold rounded-xl shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-surface-900/60 backdrop-blur-sm" onclick="toggleModal('editProductModal')"></div>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 animate-in zoom-in duration-300">
            <h3 class="text-xl font-bold text-surface-900 mb-6">Edit Produk</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" class="w-full text-sm text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Kategori</label>
                        <select name="category_id" id="edit_category_id" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                            @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">SKU</label>
                        <input type="text" name="sku" id="edit_sku" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Harga</label>
                        <input type="number" name="price" id="edit_price" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Stok</label>
                        <input type="number" name="stock" id="edit_stock" required class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Diskon (Rp)</label>
                        <input type="number" name="discount" id="edit_discount" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-surface-700 mb-1">Pajak (%)</label>
                        <input type="number" step="0.01" name="tax" id="edit_tax" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none" placeholder="0">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-surface-700 mb-1">
                            <i data-lucide="calendar-clock" class="w-4 h-4 inline mr-1"></i>
                            Tanggal Expired <span class="text-surface-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="date" name="expired_at" id="edit_expired_at" class="w-full px-4 py-2 bg-surface-50 border border-surface-200 rounded-lg outline-none focus:ring-2 focus:ring-primary-500">
                        <p class="text-xs text-surface-400 mt-1">Kosongkan jika produk tidak memiliki tanggal kedaluwarsa.</p>
                    </div>
                </div>
                <input type="hidden" name="min_stock_threshold" id="edit_threshold">
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="toggleModal('editProductModal')" class="flex-1 px-4 py-2.5 bg-surface-100 text-surface-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 premium-gradient text-white font-bold rounded-xl shadow-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.toggleModal = function(id) {
        document.getElementById(id).classList.toggle('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    window.openEditModal = function(product) {
        document.getElementById('editForm').action = `/stock/${product.id}`;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_category_id').value = product.category_id;
        document.getElementById('edit_sku').value = product.sku || '';
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_threshold').value = product.min_stock_threshold;
        document.getElementById('edit_expired_at').value = product.expired_at ? product.expired_at.split('T')[0] : '';
        document.getElementById('edit_discount').value = product.discount || 0;
        document.getElementById('edit_tax').value = product.tax || 0;
        toggleModal('editProductModal');
    }
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
