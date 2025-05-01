<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function getUserTransactions($userId)
    {
        try {
            // Get all transactions for the specific user with their details and products
            $transactions = SalesModel::with(['salesDetails.product'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Transform the transactions to include order details
            $transformedTransactions = $transactions->map(function ($transaction) {
                $orderList = $transaction->salesDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->product_id,
                        'name' => $detail->product->product_name,
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                        'totalPrice' => $detail->quantity * $detail->price
                    ];
                })->toArray();

                return [
                    'id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'order_list' => $orderList,
                    'total_order' => $transaction->total_order,
                    'status' => $transaction->status ? 'Completed' : 'Cancelled',
                    'delivery_method' => $transaction->delivery_method,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at
                ];
            });

            return response()->json($transformedTransactions);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ... other existing methods ...
}
