<?php

namespace App\Http\Controllers\Sales\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesWEBController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $dailySales = DB::table('sales')
            ->where('user_id', $userId)
            ->whereDate('created_at', today())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_order) as total_sales'))
            ->groupBy('date')
            ->get();

        $weeklySales = DB::table('sales')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_order) as total_sales'))
            ->groupBy('date')
            ->get();

        $monthlySales = DB::table('sales')
            ->where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_order) as total_sales'))
            ->groupBy('date')
            ->get();

        $yearlySales = DB::table('sales')
            ->where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_order) as total_sales'))
            ->groupBy('date')
            ->get();

        return view('Pages.Sales.index', compact('dailySales', 'weeklySales', 'monthlySales', 'yearlySales'));
    }
}
