<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales' => Transaction::sum('total_amount'),
            'total_orders' => Transaction::count(),
            'active_products' => Product::count(),
            'low_stock' => Product::where('quantity', '<', 10)->count(),
        ];

        $recent_transactions = Transaction::with('cashier')
            ->latest()
            ->take(6)
            ->get();

        // Prepare chart data for last 7 days
        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $salesData[] = Transaction::whereDate('created_at', $date)->sum('total_amount');
        }

        $category_counts = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->get();

        return view('dashboard', compact('stats', 'recent_transactions', 'category_counts', 'salesData', 'labels'));
    }

    public function reports()
    {
        $transactions = Transaction::with('cashier', 'details.product')
            ->latest()
            ->get();

        // 1. Bar Chart Data: Daily Sales (Last 14 Days)
        $dailySales = [];
        $dailyLabels = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = now()->subDays($i)->format('M d');
            $dailySales[] = Transaction::whereDate('created_at', $date)->sum('total_amount');
        }

        // 2. Pie Chart Data: Category Distribution
        $categorySales = \App\Models\TransactionDetail::join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', \Illuminate\Support\Facades\DB::raw('SUM(transaction_details.subtotal) as total'))
            ->groupBy('categories.name')
            ->get();

        $pieLabels = $categorySales->pluck('category')->toArray();
        $pieData = $categorySales->pluck('total')->toArray();
            
        return view('reports.index', compact('transactions', 'dailyLabels', 'dailySales', 'pieLabels', 'pieData'));
    }
}
