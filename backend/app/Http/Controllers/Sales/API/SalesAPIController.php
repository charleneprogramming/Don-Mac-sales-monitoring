<?php

namespace App\Http\Controllers\Sales\API;

use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use App\Infrastructure\Persistence\Eloquent\Product\ProductModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesAPIController extends Controller
{
    public function createSales(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Create the sales record
            $sale = new SalesModel();
            $sale->user_id = $request->user_id;
            $sale->total_order = $request->total_order;
            $sale->quantity = $request->quantity;
            $sale->delivery_method = $request->delivery_method;
            $sale->merchant_fee = $request->merchant_fee;
            $sale->save();

            // Process each item in the order list
            foreach ($request->order_list as $item) {
                // Update product stock
                $product = ProductModel::where('product_id', $item['product_id'])->first();
                if (!$product) {
                    throw new \Exception("Product not found: " . $item['product_id']);
                }

                if ($product->product_stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: " . $item['name']);
                }

                $product->product_stock -= $item['quantity'];
                $product->save();

                // Save order details
                DB::table('sales_details')->insert([
                    'sales_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale created successfully',
                'id' => $sale->id,
                'data' => $sale
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

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

    public function cancelSale($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Find the sale
            $sale = SalesModel::findOrFail($id);

            // Only allow cancellation of completed sales
            if (!$sale->status) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction is already cancelled'
                ], 400);
            }

            // Update sale status to cancelled
            $sale->status = false;
            $sale->save();

            // Restore product stock
            $salesDetails = DB::table('sales_details')
                ->where('sales_id', $id)
                ->get();

            foreach ($salesDetails as $detail) {
                $product = ProductModel::where('product_id', $detail->product_id)->first();
                if ($product) {
                    $product->product_stock += $detail->quantity;
                    $product->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
