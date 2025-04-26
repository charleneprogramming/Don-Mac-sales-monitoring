<?php

namespace App\Http\Controllers\Transaction\API;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use Illuminate\Http\Request;

class TransactionAPIController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userId = $request->query('user_id');

            $query = SalesModel::orderBy('created_at', 'desc');

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $transactions = $query->get();

            return response()->json($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
