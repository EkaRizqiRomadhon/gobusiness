<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function analytics()
    {
        $user = Auth::user();
        
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

        return view('pages.analytics.index', compact(
            'trends', 
            'totalSales', 
            'avgTransaction', 
            'totalTransactions', 
            'growth', 
            'categoryRevenue', 
            'dayOfWeekSales',
            'topProducts'
        ));
    }
}
