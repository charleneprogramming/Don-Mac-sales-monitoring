<?php

namespace App\Http\Controllers\Sales\API;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SalesAPIController extends Controller
{
    public function getDashboardData(): JsonResponse
    {
        $today = Carbon::today();

        $dailySales = SalesModel::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_order) as total_sales'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereDate('created_at', $today)
            ->groupBy('date')
            ->get();

        $weeklySales = SalesModel::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_order) as total_sales'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereBetween('created_at', [
                $today->copy()->subDays(6)->startOfDay(),
                $today->copy()->endOfDay(),
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlySales = SalesModel::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_order) as total_sales'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $yearlySales = SalesModel::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_order) as total_sales'),
            DB::raw('COUNT(*) as transaction_count')
        )
            ->whereYear('created_at', $today->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'dailySales' => $dailySales,
            'weeklySales' => $weeklySales,
            'monthlySales' => $monthlySales,
            'yearlySales' => $yearlySales,
        ]);
    }
}
