<?php

namespace App\Http\Controllers\Dashboard\API;

use App\Application\Sales\RegisterSales;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Sales\SalesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAPIController extends Controller
{
    // private RegisterSales $registerSales;

    // public function __construct(RegisterSales $registerSales)
    // {
    //     $this->registerSales = $registerSales;
    // }

    // public function findAll()
    // {
    //     try {
    //         $sales = $this->registerSales->findAll();

    //         return response()->json([
    //             'sales' => $sales,
    //             'message' => 'Sales retrieved successfully',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error retrieving sales',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function createSales(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Create the sale record
    //         $sale = new SalesModel;
    //         $sale->order_list = json_encode($request->order_list);
    //         $sale->total_order = $request->total_order;
    //         $sale->quantity = $request->quantity;
    //         $sale->user_id = $request->user_id;
    //         $sale->save();

    //         // Update product stock
    //         foreach ($request->order_list as $item) {
    //             $product = DB::table('product')
    //                 ->where('product_id', $item['product_id'])
    //                 ->first();

    //             if (! $product) {
    //                 DB::rollBack();

    //                 return response()->json(['error' => 'Product not found'], 404);
    //             }

    //             $newStock = $product->product_stock - $item['quantity'];

    //             if ($newStock < 0) {
    //                 DB::rollBack();

    //                 return response()->json(['error' => 'Insufficient stock for '.$item['name']], 400);
    //             }

    //             DB::table('product')
    //                 ->where('product_id', $item['product_id'])
    //                 ->update(['product_stock' => $newStock]);
    //         }

    //         DB::commit();

    //         return response()->json(['message' => 'Sale created successfully'], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function findByUserID($userID)
    // {
    //     $sales = $this->registerSales->findByUserID($userID);

    //     return response()->json($sales);
    // }

    // public function getProducts($userId)
    // {
    //     try {
    //         // Get all available products
    //         $products = DB::table('product')
    //             ->where('user_id', $userId)
    //             ->where('product_stock', '>', 0)
    //             ->select(
    //                 'product_id',
    //                 'product_name',
    //                 'product_price',
    //                 'product_stock',
    //                 'product_image'
    //             )
    //             ->get();

    //         return response()->json([
    //             'products' => $products,
    //             'message' => 'Products retrieved successfully'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error retrieving products',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
