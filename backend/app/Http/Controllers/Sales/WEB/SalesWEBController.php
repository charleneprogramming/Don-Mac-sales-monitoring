<?php

namespace App\Http\Controllers\Sales\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesWEBController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Daily Sales (Today) - Only Completed Transactions
        $dailySales = DB::table('sales')
            ->whereDate('created_at', $today)
            ->where('status', true) // Only completed transactions
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_order) as total_sales'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 1 THEN 1 ELSE 0 END) as delivery_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 0 THEN 1 ELSE 0 END) as pickup_count'),
                DB::raw('SUM(merchant_fee) as total_delivery_fees')
            )
            ->groupBy('date')
            ->get();

        // Yesterday's sales for comparison - Only Completed Transactions
        $yesterdaySales = DB::table('sales')
            ->whereDate('created_at', $yesterday)
            ->where('status', true) // Only completed transactions
            ->select(DB::raw('SUM(total_order) as total_sales'))
            ->first();

        // Weekly Sales (Last 7 days) - Only Completed Transactions
        $weeklySales = DB::table('sales')
            ->whereBetween('created_at', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay()
            ])
            ->where('status', true) // Only completed transactions
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_order) as total_sales'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 1 THEN 1 ELSE 0 END) as delivery_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 0 THEN 1 ELSE 0 END) as pickup_count'),
                DB::raw('SUM(merchant_fee) as total_delivery_fees')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly Sales (Current Month) - Only Completed Transactions
        $monthlySales = DB::table('sales')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('status', true) // Only completed transactions
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_order) as total_sales'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 1 THEN 1 ELSE 0 END) as delivery_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 0 THEN 1 ELSE 0 END) as pickup_count'),
                DB::raw('SUM(merchant_fee) as total_delivery_fees')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Yearly Sales (Current Year) - Only Completed Transactions
        $yearlySales = DB::table('sales')
            ->whereYear('created_at', now()->year)
            ->where('status', true) // Only completed transactions
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_order) as total_sales'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 1 THEN 1 ELSE 0 END) as delivery_count'),
                DB::raw('SUM(CASE WHEN delivery_method = 0 THEN 1 ELSE 0 END) as pickup_count'),
                DB::raw('SUM(merchant_fee) as total_delivery_fees')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calculate trends - Only for Completed Transactions
        $todayTotal = $dailySales->sum('total_sales') ?? 0;
        $yesterdayTotal = $yesterdaySales->total_sales ?? 0;
        $dailyTrend = $yesterdayTotal > 0 ? (($todayTotal - $yesterdayTotal) / $yesterdayTotal) * 100 : 0;

        // Add trend information to the view data
        $viewData = compact('dailySales', 'weeklySales', 'monthlySales', 'yearlySales');
        $viewData['dailyTrend'] = $dailyTrend;

        return view('Pages.Sales.index', $viewData);
    }
}
