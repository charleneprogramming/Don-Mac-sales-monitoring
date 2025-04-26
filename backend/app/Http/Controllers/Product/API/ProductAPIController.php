<?php

namespace App\Http\Controllers\Product\API;

use App\Application\Product\RegisterProducts;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductAPIController extends Controller
{
    private RegisterProducts $registerProducts;

    public function __construct(RegisterProducts $registerProducts)
    {
        $this->registerProducts = $registerProducts;
    }

    public function findAll()
    {
        try {
            $productModels = $this->registerProducts->findAll();
            if (empty($productModels)) {
                return response()->json(['products' => []], 200);
            }

            $products = array_map(function ($product) {
                return [
                    'product_id' => $product->getProduct_id(),
                    'product_name' => $product->getProduct_name(),
                    'product_price' => $product->getProduct_price(),
                    'product_stock' => $product->getProduct_stock(),
                    'description' => $product->getDescription(),
                    'product_image' => $product->getProduct_image() ?? 'default.jpg',
                    'userID' => $product->getUserID(),
                ];
            }, $productModels);

            return response()->json(['products' => $products], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createProducts(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'productName' => 'required|string',
            'productPrice' => 'required|numeric',
            'productStock' => 'required|numeric',
            'productImage' => 'nullable',
            'productDescription' => 'required|string',
            'userID' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        if ($request->file('image')) {
            $image = $request->file('image');
            $destinationpath = 'images';

            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationpath, $imageName);

            $data['image'] = $imageName;
        } else {
            $data['image'] = 'default.jpg';
        }

        $this->registerProducts->create(
            $this->generateProductId(),
            $data['productName'],
            $data['productPrice'],
            'default.jpg',
            $data['productStock'],
            $data['productDescription'],
            $data['userID'],
        );

        return response()->json(['message' => 'test']);
    }

    public function generateProductId()
    {
        do {
            $product_id = $this->generateRandomAlphaNumeric(15);
        } while ($this->registerProducts->findByProductID($product_id));

        return $product_id;
    }

    public function generateRandomAlphaNumeric(int $length = 15): string
    {
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
    }


    public function destroy($id)
    {
        try {
            $userId = auth()->id();
            $products = $this->registerProducts->findAll();
            $product = collect($products)->first(function ($product) use ($id) {
                return $product->getId() == $id;
            });

            if (! $product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            if ($product->getUserID() !== $userId) {
                return response()->json(['message' => 'Unauthorized to delete this product'], 403);
            }

            $this->registerProducts->delete($product->getProduct_id());

            return response()->json([
                'message' => 'Product archived successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error archiving product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $products = DB::table('product')
                ->whereNull('deleted_at')
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // public function findByUserID($user_id): JsonResponse
    // {
    //     try {
    //         $products = $this->registerProducts->findByUserID((int) $user_id);

    //         return response()->json($products);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    

    public function findByUserID($user_id): JsonResponse
    {
        $products = $this->registerProducts->findByUserID((int) $user_id);

        if (empty($products)) {
            $products = [];
        } else {
            $products = array_map(function ($product) {
                return [
                    'product_id' => $product->getProduct_id(),
                    'product_name' => $product->getProduct_name(),
                    'product_price' => $product->getProduct_price(),
                    'product_stock' => $product->getProduct_stock(),
                    'description' => $product->getDescription(),
                    'product_image' => $product->getProduct_image() ?? 'default.jpg',
                ];
            }, $products);
        }

        return response()->json($products);
        //     try {
        //         // Get the authenticated user from the request
        //         $authenticatedUser = request()->user;

        //         // Check if the user is trying to access their own products
        //         if ($authenticatedUser->id != $user_id) {
        //             return response()->json([
        //                 'message' => 'Unauthorized access',
        //             ], 403);
        //         }

        //         $products = $this->registerProducts->findByUserID((int) $user_id);

        //         if (empty($products)) {
        //             return response()->json(['products' => []], 200);
        //         }

        //         $formattedProducts = array_map(function ($product) {
        //             return [
        //                 'product_id' => $product->getProduct_id(),
        //                 'product_name' => $product->getProduct_name(),
        //                 'description' => $product->getDescription(),
        //                 'product_price' => $product->getProduct_price(),
        //                 'product_stock' => $product->getProduct_stock(),
        //                 'product_image' => $product->getProduct_image(),
        //                 'userID' => $product->getUserID(),
        //             ];
        //         }, $products);

        //         return response()->json(['products' => $formattedProducts], 200);
        //     } catch (\Exception $e) {
        //         return response()->json([
        //             'message' => 'Error fetching products',
        //             'error' => $e->getMessage(),
        //         ], 500);
        //     }
    }
}
