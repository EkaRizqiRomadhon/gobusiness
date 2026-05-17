@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-surface-900">Rekapitulasi Penjualan</h2>
            <p class="text-surface-500 text-sm">Pantau performa harian dan ringkasan keuangan bisnis Anda.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export') }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 transition-all shadow-md active:scale-95">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>
                Export Excel (CSV)
            </a>
            <button onclick="window.print()" class="flex items-center px-4 py-2 bg-white border border-surface-200 rounded-lg text-sm font-medium text-surface-700 hover:bg-surface-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $totalAllTime = $dailyReports->sum('net');
            $totalTrx = $dailyReports->sum('trx_count');
            $avgTrx = $totalTrx > 0 ? $totalAllTime / $totalTrx : 0;
        @endphp
        <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm">
            <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Total Omzet</p>
            <h3 class="text-xl font-bold text-surface-900">Rp {{ number_format($totalAllTime, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm">
            <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-xl font-bold text-surface-900">{{ number_format($totalTrx, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm">
            <p class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-1">Rata-rata/Trx</p>
            <h3 class="text-xl font-bold text-surface-900">Rp {{ number_format($avgTrx, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-surface-200 flex items-center justify-between">
            <h3 class="text-base font-bold text-surface-900">Detail Penjualan Harian</h3>
            <span class="px-2 py-0.5 bg-surface-100 text-surface-600 text-[9px] font-bold uppercase rounded-full">Rekapitulasi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 border-b border-surface-200">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">Jumlah Trx</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse($dailyReports as $report)
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-surface-900">
                            {{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-surface-600 font-medium">
                            {{ $report->trx_count }} Transaksi
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-primary-600 text-right">
                            Rp {{ number_format($report->net, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-10 text-center text-surface-400">
                            <p class="text-sm">Belum ada data penjualan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Individual Transactions History -->
    <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-surface-200 flex items-center justify-between">
            <h3 class="text-base font-bold text-surface-900">Riwayat Transaksi</h3>
            <span class="px-2 py-0.5 bg-primary-50 text-primary-600 text-[9px] font-bold uppercase rounded-full">Real-time</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-50 border-b border-surface-200">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">ID Trx</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">Metode</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-surface-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-surface-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <p class="text-xs font-bold text-surface-900">{{ $trx->created_at->format('d/m/y') }}</p>
                            <p class="text-[9px] text-surface-400 font-bold uppercase">{{ $trx->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-[10px] font-bold text-surface-400">#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($trx->payment_method === 'cash')
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md">CASH</span>
                                @else
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">QRIS</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-surface-900">
                            Rp {{ number_format($trx->net_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <button onclick="showTrxDetail({{ json_encode($trx) }})" class="p-1.5 hover:bg-surface-100 rounded-lg text-primary-600 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-surface-400">
                            <p>Belum ada transaksi individu.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="p-6 border-t border-surface-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="trxModal"
     class="fixed inset-0 z-50 hidden backdrop-blur-sm"
     style="background: rgba(0,0,0,0.55); display: none; align-items: center; justify-content: center; padding: 16px;">
    <!-- Backdrop -->
    <div class="absolute inset-0" onclick="hideTrxModal()"></div>

    <!-- Modal Box -->
    <div class="relative bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden"
         style="width: 100%; max-width: 420px; max-height: 85vh;">

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-gray-100 bg-white shrink-0 px-5 py-4">
            <div>
                <h4 class="text-base font-bold text-gray-900">Detail Transaksi</h4>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest" id="modalTrxIdLabel"></p>
            </div>
            <button onclick="hideTrxModal()"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-400 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto flex-1 px-5 py-4 space-y-4 bg-white custom-scrollbar">
            <!-- Status & Time -->
            <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md uppercase">Lunas</span>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Transaksi</p>
                    <p class="text-xs font-bold text-gray-900" id="modalTrxDate"></p>
                </div>
            </div>

            <!-- Items -->
            <div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-3">Rincian Item</p>
                <div class="space-y-3" id="modalItemList"></div>
            </div>

            <!-- Payment Method -->
            <div class="pt-3 border-t border-gray-100 space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Metode Pembayaran</span>
                    <div class="flex items-center text-xs font-bold text-gray-800" id="modalPaymentMethod"></div>
                </div>
                <div id="modalRefRow" class="hidden justify-between items-center">
                    <span class="text-xs text-gray-500">No. Referensi</span>
                    <span class="text-xs font-mono font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded" id="modalRefNumber"></span>
                </div>
            </div>

            <!-- Payment Proof Image -->
            <div id="modalProofRow" class="hidden pt-4 border-t border-gray-100">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Bukti Pembayaran</p>
                <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                    <img id="modalProofImage" src="" alt="Bukti Pembayaran" class="w-full h-auto max-h-64 object-contain">
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 shrink-0">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-bold text-gray-700">Total Pembayaran</span>
                <span class="text-xl font-extrabold text-primary-600" id="modalTotalAmount"></span>
            </div>
            <button onclick="hideTrxModal()"
                    class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all active:scale-[0.98]">
                Tutup Detail
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    const trxModal = document.getElementById('trxModal');

    function showTrxDetail(trx) {
        // ID & Date
        document.getElementById('modalTrxIdLabel').innerText = '#TRX-' + String(trx.id).padStart(5, '0');
        document.getElementById('modalTrxDate').innerText = new Date(trx.created_at).toLocaleString('id-ID', { 
            dateStyle: 'long', 
            timeStyle: 'short',
            timeZone: 'Asia/Jakarta'
        }) + ' WIB';
        
        // Payment Method
        const methodContainer = document.getElementById('modalPaymentMethod');
        if (trx.payment_method === 'cash') {
            methodContainer.innerHTML = '<i data-lucide="banknote" class="w-4 h-4 mr-2 text-green-600"></i> Tunai';
        } else {
            methodContainer.innerHTML = '<i data-lucide="qr-code" class="w-4 h-4 mr-2 text-blue-600"></i> QRIS';
        }

        // Total
        document.getElementById('modalTotalAmount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(trx.net_amount);
        
        // Reference Number
        const refRow = document.getElementById('modalRefRow');
        if (trx.reference_number) {
            refRow.classList.replace('hidden', 'flex');
            document.getElementById('modalRefNumber').innerText = trx.reference_number;
        } else {
            refRow.classList.replace('flex', 'hidden');
        }

        // Payment Proof
        const proofRow = document.getElementById('modalProofRow');
        const proofImage = document.getElementById('modalProofImage');
        if (trx.payment_proof) {
            proofRow.classList.remove('hidden');
            proofImage.src = '/storage/' + trx.payment_proof;
        } else {
            proofRow.classList.add('hidden');
            proofImage.src = '';
        }

        // Items List
        const itemList = document.getElementById('modalItemList');
        itemList.innerHTML = trx.items.map(item => `
            <div class="flex justify-between items-center group">
                <div class="flex-1 pr-4">
                    <p class="text-sm font-bold text-surface-900 group-hover:text-primary-600 transition-colors">${item.product ? item.product.name : 'Produk Terhapus'}</p>
                    <p class="text-[11px] text-surface-500 font-medium">${item.quantity} x Rp ${new Intl.NumberFormat('id-ID').format(item.price_at_time)}</p>
                </div>
                <p class="text-sm font-bold text-surface-900">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</p>
            </div>
        `).join('');

        // Show Modal with animation
        trxModal.style.display = 'flex';
        trxModal.classList.remove('hidden');
        
        // Re-init icons
        lucide.createIcons();
    }

    function hideTrxModal() {
        trxModal.style.display = 'none';
        trxModal.classList.add('hidden');
    }

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && trxModal.style.display !== 'none') {
            hideTrxModal();
        }
    });
</script>
@endsection
