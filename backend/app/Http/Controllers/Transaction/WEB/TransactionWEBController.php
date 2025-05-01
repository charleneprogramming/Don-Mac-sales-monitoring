<?php

namespace App\Http\Controllers\Transaction\WEB;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Illuminate\Support\Facades\DB;

class TransactionWEBController extends Controller
{
    public function index()
    {
        // Get all users
        $users = UserModel::all();
        $totalUsers = $users->count();

        // Get all sales with their details
        $transactions = SalesModel::with(['user', 'salesDetails.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'id' => $sale->id,
                    'name' => $sale->user->name,
                    'username' => $sale->user->username,
                    'contact' => $sale->user->contact_number,
                    'order_date' => $sale->created_at->format('Y-m-d H:i:s'),
                    'delivery_method' => $sale->delivery_method,
                    'merchant_fee' => $sale->delivery_method ? 25.00 : 0.00,
                    'status' => $sale->status,
                    'beverages' => $sale->salesDetails->map(function ($detail) {
                        return [
                            'name' => $detail->product->product_name,
                            'quantity' => $detail->quantity,
                            'price' => $detail->price
                        ];
                    })->toArray(),
                    'total' => $sale->total_order
                ];
            });

        // Calculate summary statistics
        $totalSold = $transactions->where('status', true)->sum('total');
        $totalTransactions = $transactions->count();
        $completedCount = $transactions->where('status', true)->count();
        $cancelledCount = $transactions->where('status', false)->count();

        return view('Pages.Transaction.index', compact(
            'users',
            'transactions',
            'completedCount',
            'cancelledCount',
            'totalSold',
            'totalTransactions',
            'totalUsers'
        ));
    }

    public function getUserTransactions($userId)
    {
        $transactions = SalesModel::with(['user', 'salesDetails.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'date' => $sale->created_at->format('Y-m-d'),
                    'time' => $sale->created_at->format('H:i:s'),
                    'delivery_method' => $sale->delivery_method,
                    'merchant_fee' => $sale->merchant_fee,
                    'status' => $sale->status,
                    'total' => $sale->total_order
                ];
            });

        return response()->json(['transactions' => $transactions]);
    }

    public function getTransactionDetails($transactionId)
    {
        $transaction = SalesModel::with(['user', 'salesDetails.product'])
            ->find($transactionId);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Set merchant fee based on delivery method
        $merchantFee = $transaction->delivery_method ? 25.00 : 0.00;

        // Calculate subtotal from beverages
        $beverages = $transaction->salesDetails->map(function ($detail) {
            $price = (float) $detail->price;
            $quantity = (int) $detail->quantity;
            return [
                'name' => $detail->product->product_name,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $price * $quantity
            ];
        })->toArray();

        $subtotal = collect($beverages)->sum('subtotal');

        $details = [
            'id' => $transaction->id,
            'user' => [
                'name' => $transaction->user->name,
                'username' => $transaction->user->username,
                'contact' => $transaction->user->contact_number
            ],
            'order_date' => $transaction->created_at->format('Y-m-d H:i:s'),
            'delivery_method' => $transaction->delivery_method,
            'merchant_fee' => $merchantFee,
            'status' => $transaction->status,
            'beverages' => $beverages,
            'subtotal' => $subtotal,
            'total_with_fee' => $subtotal + $merchantFee
        ];

        return response()->json($details);
    }

    public function updateTransactionStatus($transactionId)
    {
        $transaction = SalesModel::find($transactionId);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        $transaction->status = !$transaction->status;
        $transaction->save();

        return response()->json([
            'success' => true,
            'newStatus' => $transaction->status,
            'message' => 'Transaction status updated successfully'
        ]);
    }
}
