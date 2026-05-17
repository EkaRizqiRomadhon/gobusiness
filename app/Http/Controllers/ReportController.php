<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_sales' => $user->transactions()->whereDate('created_at', today())->sum('net_amount'),
            'transaction_count' => $user->transactions()->whereDate('created_at', today())->count(),
            'total_products_count' => $user->products()->count(),
            'low_stock_count' => $user->products()->whereColumn('stock', '<=', 'min_stock_threshold')->count(),
            'new_customers' => 42,
        ];

        $recentTransactions = $user->transactions()->latest()->take(5)->get();
        
        $bestSellers = $user->products()
            ->withSum('transactionItems', 'quantity')
            ->orderBy('transaction_items_sum_quantity', 'desc')
            ->take(3)
            ->get();

        // Data Tren Mingguan (Selalu 7 hari terakhir)
        $weeklyTrendData = $user->transactions()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(net_amount) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $weeklyTrend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $weeklyTrend->push((object)[
                'date' => $date,
                'total' => $weeklyTrendData->get($date, 0)
            ]);
        }

        return view('pages.dashboard', compact('stats', 'recentTransactions', 'bestSellers', 'weeklyTrend'));
    }

    public function reports()
    {
        $user = Auth::user();
        
        // Ringkasan Harian (Rekap)
        $dailyReports = $user->transactions()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as trx_count'),
                DB::raw('SUM(total_amount) as gross'),
                DB::raw('SUM(tax) as tax_total'),
                DB::raw('SUM(net_amount) as net')
            )
            ->groupBy('date')
            ->latest('date')
            ->get();

        // Riwayat Transaksi Individual
        $transactions = $user->transactions()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('pages.reports.index', compact('dailyReports', 'transactions'));
    }

    public function export()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->with(['items.product'])->latest()->get();

        $filename = "laporan-penjualan-" . now()->format('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $user = Auth::user();
            $transactions = $user->transactions()->with(['items.product'])->latest()->get();
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom
            fputcsv($file, ['ID Transaksi', 'Tanggal', 'Waktu', 'Metode Pembayaran', 'No. Referensi', 'Total Transaksi', 'Pajak', 'Total Bayar', 'Bukti Pembayaran', 'Rincian Produk']);

            foreach ($transactions as $trx) {
                $items = $trx->items->map(function($item) {
                    return ($item->product->name ?? 'Produk Terhapus') . " (" . $item->quantity . "x)";
                })->implode(', ');

                fputcsv($file, [
                    'TRX-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT),
                    $trx->created_at->format('d/m/Y'),
                    $trx->created_at->format('H:i'),
                    strtoupper($trx->payment_method),
                    $trx->reference_number ?? '-',
                    $trx->total_amount,
                    $trx->tax,
                    $trx->net_amount,
                    $trx->payment_proof ? url('storage/' . $trx->payment_proof) : '-',
                    $items
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->get('year', now()->year);
        
        // 1. Line Chart: Daily Sales last 14 days for better trend
        $trendsData = $user->transactions()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(net_amount) as total'))
            ->where('created_at', '>=', now()->subDays(13))
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $trends = collect();
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trends->push((object)[
                'date' => $date,
                'day' => now()->subDays($i)->format('D'),
                'total' => $trendsData->get($date, 0)
            ]);
        }

        // 2. Summary Stats
        $totalSales = $user->transactions()->sum('net_amount');
        $avgTransaction = $user->transactions()->avg('net_amount') ?: 0;
        $totalTransactions = $user->transactions()->count();
        
        // Growth Calculation (This 7 days vs Last 7 days)
        $thisWeekSales = $user->transactions()->where('created_at', '>=', now()->subDays(6))->sum('net_amount');
        $lastWeekSales = $user->transactions()->whereBetween('created_at', [now()->subDays(13), now()->subDays(7)])->sum('net_amount');
        $growth = $lastWeekSales > 0 ? (($thisWeekSales - $lastWeekSales) / $lastWeekSales) * 100 : 0;

        // 3. Category Distribution (by Revenue instead of just product count)
        $categoryRevenue = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('categories.user_id', $user->id)
            ->select('categories.name', DB::raw('SUM(transaction_items.subtotal) as total_revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // 4. Sales by Day of Week
        $dayOfWeekSales = $user->transactions()
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('SUM(net_amount) as total'))
            ->groupBy('day')
            ->get()
            ->pluck('total', 'day');

        // 5. Top 5 Products by Revenue
        $topProducts = $user->products()
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->select('products.name', DB::raw('SUM(transaction_items.subtotal) as revenue'), DB::raw('SUM(transaction_items.quantity) as sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        // 6. NEW: Monthly Sales Chart for the selected year
        $monthlySalesData = $user->transactions()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(net_amount) as total_sales'),
                DB::raw('SUM(net_amount) - SUM(total_amount - net_amount) as total_profit'),
                DB::raw('COUNT(*) as trx_count')
            )
            ->whereYear('created_at', $selectedYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        // Build monthly data for all 12 months
        $monthlyChart = collect();
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        for ($m = 1; $m <= 12; $m++) {
            $data = $monthlySalesData->get($m);
            
            // Find best-selling product for this month
            $bestProduct = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->where('transactions.user_id', $user->id)
                ->whereYear('transactions.created_at', $selectedYear)
                ->whereMonth('transactions.created_at', $m)
                ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_qty', 'desc')
                ->first();

            $monthlyChart->push((object)[
                'month' => $m,
                'month_name' => $monthNames[$m - 1],
                'total_sales' => $data->total_sales ?? 0,
                'total_profit' => $data->total_profit ?? 0,
                'trx_count' => $data->trx_count ?? 0,
                'best_product' => $bestProduct->name ?? '-',
                'best_product_qty' => $bestProduct->total_qty ?? 0,
            ]);
        }

        // 7. NEW: Product Performance for Pie Chart (real-time all-time data)
        $productPerformance = $user->products()
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('COALESCE(SUM(transaction_items.subtotal), 0) as total_revenue'),
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        $totalProductRevenue = $productPerformance->sum('total_revenue');

        // Available years for the year selector
        $availableYears = $user->transactions()
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        return view('pages.analytics.index', compact(
            'trends', 
            'totalSales', 
            'avgTransaction', 
            'totalTransactions', 
            'growth', 
            'categoryRevenue', 
            'dayOfWeekSales',
            'topProducts',
            'monthlyChart',
            'selectedYear',
            'availableYears',
            'productPerformance',
            'totalProductRevenue'
        ));
    }
}
