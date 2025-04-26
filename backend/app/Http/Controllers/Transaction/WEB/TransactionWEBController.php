<?php

namespace App\Http\Controllers\Transaction\WEB;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;

class TransactionWEBController extends Controller
{


public function index()
{
    $users = [
        (object) [
            'id' => 1,
            'name' => 'John Doe',
            'username' => 'john.doe@example.com',
            'contact' => '+1234567890',
        ],
        (object) [
            'id' => 2,
            'name' => 'Jane Smith',
            'username' => 'jane.smith@example.com',
            'contact' => '+9876543210',
        ],
        (object) [
            'id' => 3,
            'name' => 'Alice Johnson',
            'username' => 'alice.johnson@example.com',
            'contact' => '+1122334455',
        ],
    ];

    // Mock transactions for testing
    $transactions = [
        (object) [
            'id' => 101,
            'name' => 'John Doe',
            'username' => 'john.doe@example.com',
            'contact' => '+1234567890',
            'order_date' => '2023-10-01 10:20:30',
            'delivery_method' => false,
            'merchant_fee' => false,
            'status' => true,
            'beverages' => [
                ['name' => 'Product A', 'quantity' => 2, 'price' => 39.00],
                ['name' => 'Product B', 'quantity' => 1, 'price' => 39.00],
            ],
        ],
        (object) [
            'id' => 102,
            'name' => 'Jane Smith',
            'username' => 'jane.smith@example.com',
            'contact' => '+9876543210',
            'order_date' => '2023-10-02 10:20:30',
            'delivery_method' => false,
            'merchant_fee' => 0.00,
            'status' => true,
            'beverages' => [
                ['name' => 'Product C', 'quantity' => 4, 'price' => 39.00],
            ],
        ],
        (object) [
            'id' => 103,
            'name' => 'Alice Johnson',
            'username' => 'alice.johnson@example.com',
            'contact' => '+9876543210',
            'order_date' => '2023-10-02 10:20:30',
            'delivery_method' => false,
            'merchant_fee' => 0.00,
            'status' => false,
            'beverages' => [
                ['name' => 'Product D', 'quantity' => 5, 'price' => 39.00],
            ],
        ],
    ];
      // Calculate counts
      $completedCount = count(array_filter($transactions, fn($transaction) => $transaction->status));
      $cancelledCount = count(array_filter($transactions, fn($transaction) => !$transaction->status));
      

    // Calculate the total for each transaction
    foreach ($transactions as $transaction) {
        $total = 0;

        if (isset($transaction->beverages) && is_array($transaction->beverages)) {
            foreach ($transaction->beverages as $beverage) {
                $total += $beverage['quantity'] * $beverage['price'];
            }
        }

        if ($transaction->delivery_method) {
            $total += 25;
        }

        $transaction->total = $total;
    }

    

    // Pass the users and transactions to the view
    return view('Pages.Transaction.index', compact('users', 'transactions'));
}

     
        public function getTransactionDetails($transactionId)
        {
            // Mock transaction details for testing
            $transactionDetails = [
                101 => [
                    'delivery_method' => false,
                    'merchant_fee' => false,
                    'beverages' => [
                        ['name' => 'Product A', 'quantity' => 2, 'price' => 39.00],
                        ['name' => 'Product B', 'quantity' => 1, 'price' => 39.00],
                    ],
                ],
                102 => [
                    'delivery_method' => false,
                    'merchant_fee' => false,
                    'beverages' => [
                        ['name' => 'Product C', 'quantity' => 4, 'price' => 39.00],
                    ],
                ],
                103 => [
                    'delivery_method' => false,
                    'merchant_fee' => false,
                    'beverages' => [
                        ['name' => 'Product D', 'quantity' => 5, 'price' => 39.00],
                    ],
                ],
            ];
        
            $transaction = $transactionDetails[$transactionId] ?? null;
        
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            $transaction['merchant_fee'] ? 25.00 : 0.00;
        
            return response()->json($transaction);
        }
        
    
    // public function index($user_id)
    // {
    //     $transactions = SalesModel::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

    //     return view('Pages.Transaction.index', compact('transactions'));
    // }
// }
    }