<?php

namespace App\Http\Controllers\Dashboard\WEB;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardWEBController extends Controller
{
    public function index()
    {
        // Get all users
        $users = UserModel::all();
        $totalUsers = $users->count();

        // Get today's date range
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        // Get all sales with their details
        $transactions = SalesModel::with(['user', 'salesDetails.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate basic statistics
        $totalTransactions = $transactions->count();
        $completedTransactions = $transactions->where('status', true);
        $completedCount = $completedTransactions->count();
        $cancelledCount = $transactions->where('status', false)->count();

        // Calculate total revenue (only from completed transactions)
        $totalSold = $completedTransactions->sum('total_order');

        // Calculate delivery statistics
        $deliveryCount = $completedTransactions->where('delivery_method', true)->count();
        $pickupCount = $completedTransactions->where('delivery_method', false)->count();

        // Calculate merchant fee total (delivery fees)
        $merchantFeeTotal = $completedTransactions->where('delivery_method', true)
            ->count() * 25.00; // 25.00 is the fixed delivery fee

        // Get today's statistics
        $todayTransactions = $completedTransactions->filter(function($transaction) use ($todayStart) {
            return Carbon::parse($transaction->created_at)->isToday();
        });
        $todayRevenue = $todayTransactions->sum('total_order');
        $todayCount = $todayTransactions->count();

        // Get this week's statistics
        $thisWeekTransactions = $completedTransactions->filter(function($transaction) {
            return Carbon::parse($transaction->created_at)->isCurrentWeek();
        });
        $weeklyRevenue = $thisWeekTransactions->sum('total_order');
        $weeklyCount = $thisWeekTransactions->count();

        return view('Pages.Dashboard.index', compact(
            'totalSold',
            'totalTransactions',
            'totalUsers',
            'completedCount',
            'cancelledCount',
            'deliveryCount',
            'pickupCount',
            'merchantFeeTotal',
            'todayRevenue',
            'todayCount',
            'weeklyRevenue',
            'weeklyCount'
        ));
    }

    // public function updateStock(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $orders = $request->input('orders');
    //         $totalQuantity = 0;
    //         $totalOrder = 0;

    //         foreach ($orders as $order) {
    //             $product = DB::table('product')
    //                 ->where('product_name', $order['name'])
    //                 ->where('userID', Auth::id())
    //                 ->first();

    //             if ($product) {
    //                 $newStock = $product->product_stock - $order['quantity'];

    //                 if ($newStock < 0) {
    //                     DB::rollBack();

    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => "Insufficient stock for {$order['name']}",
    //                     ], 400);
    //                 }

    //                 DB::table('product')
    //                     ->where('product_name', $order['name'])
    //                     ->where('userID', Auth::id())
    //                     ->update(['product_stock' => $newStock]);

    //                 $totalQuantity += $order['quantity'];
    //                 $totalOrder += $order['price'] * $order['quantity'];
    //             }
    //         }

    //         // Create transaction record with userID
    //         $sale = new SalesModel;
    //         $sale->order_list = json_encode($orders);
    //         $sale->total_order = $totalOrder;
    //         $sale->quantity = $totalQuantity;
    //         $sale->user_id = Auth::id();
    //         $sale->save();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'transaction_id' => $sale->id,
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
