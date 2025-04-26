<?php

namespace App\Http\Controllers\Product\WEB;

use App\Application\Product\RegisterProducts;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\Product\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductWebController extends Controller
{
    private RegisterProducts $registerProducts;

    public function __construct(RegisterProducts $registerProducts)
    {
        $this->registerProducts = $registerProducts;
    }

    public function index($user_id)
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

        return view('Pages.Product.index', compact('products'));
    }

    public function findAll()
    {
        try {
            $productModel = $this->registerProducts->findAll();
            if (! $productModel) {
                return response()->json(['message' => 'No products found.'], 404);
            }

            $products = array_map(fn ($productModel) => $productModel->toArray(), $productModel);

            return response()->json(compact('products'), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    

    public function createProducts(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'product_name' => 'required|string',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'required|integer|min:0',
            'productImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'productDescription' => 'required|string',
        ]);

        if ($validate->fails()) {

            return redirect()->route('product.index', ['user_id' => $request->user_id])
                ->with('error', $validate->errors()->first());

        }
        $validateProductName = $this->registerProducts->findByProductNameAndUserID($request->product_name, $request->user_id);
        if ($validateProductName) {
            return redirect()->route('product.index', ['user_id' => $request->user_id])
                ->with('error', 'Product name is taken');
        }
        try {
            $productId = $this->generateProductId();
            $imageName = 'default.jpg';

            if ($request->hasFile('productImage')) {
                $image = $request->file('productImage');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
            }

            $this->registerProducts->create(
                $productId,
                $request->product_name,
                (float) $request->productPrice,
                $imageName,
                (int) $request->productStock,
                $request->productDescription,
                $request->user_id,
            );

            return redirect()->route('product.index', ['user_id' => $request->user_id]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => [$e->getMessage()]]], 500);
        }
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

    public function updateProduct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'productID' => 'required|string',
            'productName' => [
                'required',
                'string',
                Rule::unique('product', 'product_name')
                    ->where(function ($query) use ($request) {
                        return $query->where('userID', $request->user_id)
                            ->whereNull('deleted_at');
                    })
                    ->ignore($request->productID, 'product_id'),
            ],
            'productPrice' => 'required|numeric',
            'productStock' => 'required|numeric',
            'productImage' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
            'productDescription' => 'required|string',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        try {
            $data = $request->all();
            $existingProduct = $this->registerProducts->findByProductID($data['productID']);

            if (!$existingProduct) {
                return response()->json(['errors' => ['general' => ['Product not found']]], 404);
            }

            if ($request->hasFile('productImage')) {
                if ($existingProduct->getProduct_image() !== 'default.jpg') {
                    File::delete(public_path('images/' . $existingProduct->getProduct_image()));
                }
                $image = $request->file('productImage');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $data['image'] = $imageName;
            } else {
                $data['image'] = $existingProduct->getProduct_image() ?? 'default.jpg';
            }

            $this->registerProducts->update(
                $data['productID'],
                $data['productName'],
                (float)$data['productPrice'],
                $data['image'],
                (int)$data['productStock'],
                $data['productDescription'],
                $data['user_id'],
            );

            return redirect()->route('product.index', ['user_id' => $data['user_id']])
                ->with('success', 'Beverage updated successfully!');
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => [$e->getMessage()]]], 500);
        }
    }
    
    public function deleteitem($id)
    {
        try {
            $product = ProductModel::where('product_id', $id)->first();
            
            if ($product) {
                // Store user_id before deletion
                $userId = $product->userID;

                // Soft delete the product
                $product->delete();

                // Verify the product was soft deleted
                if ($product->trashed()) {
                    \Log::info('Product soft deleted: ', [
                        'product_id' => $id,
                        'trashed' => $product->trashed(),
                        'deleted_at' => $product->deleted_at,
                    ]);

                    return redirect()->route('product.index', ['user_id' => $userId])
                        ->with('success', 'Succesfully moved to archive');
                }
            }

            return redirect()->route('product.index', ['user_id' => auth()->id()])
                ->with('error', 'Failed to archive product');
        } catch (\Exception $e) {
            return redirect()->route('product.index', ['user_id' => auth()->id()])
                ->with('error', 'Error archiving product: '.$e->getMessage());
        }
    }

    public function checkProductName(Request $request)
    {
        $exists = $this->registerProducts->findByProductName($request->productName) !== null;

        return response()->json(['exists' => $exists]);
    }

    public function validateProductImage(Request $request)
    {
        if (! $request->hasFile('productImage')) {
            return response()->json(['exists' => false]);
        }

        try {
            $image = $request->file('productImage');
            $imageHash = md5_file($image->getPathname());

            // Get all products from database
            $products = $this->registerProducts->findAll();

            foreach ($products as $product) {
                $existingImagePath = public_path('images/'.$product->getProduct_image());

                if (file_exists($existingImagePath) && $product->getProduct_image() !== 'default.jpg') {
                    $existingHash = md5_file($existingImagePath);

                    if ($existingHash === $imageHash) {
                        return response()->json([
                            'exists' => true,
                            'productName' => $product->getProduct_name(),
                        ]);
                    }
                }
            }

            return response()->json(['exists' => false]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function archive()
    {
        try {
            $archivedProducts = ProductModel::onlyTrashed()->get()->map(function ($product) {
                return [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'product_stock' => $product->product_stock,
                    'description' => $product->description,
                    'product_image' => $product->product_image ?? 'default.jpg',
                    'user_id' => $product->userID,
                ];
            })->toArray();

            $userId = auth()->id(); // Get the authenticated user's ID

            return view('Pages.Archive.index', compact('archivedProducts', 'userId'));
        } catch (\Exception $e) {
            \Log::error('Archive error: '.$e->getMessage());

            return view('Pages.Archive.index', ['archivedProducts' => [], 'userId' => auth()->id()]);
        }
    }

    public function restore($product_id)
    {
        try {
            $userId = auth()->id();
            $product = ProductModel::onlyTrashed()
                ->where('product_id', $product_id)
                ->where('userID', $userId)
                ->first();

            if (! $product) {
                return redirect()->route('product.archive')
                    ->with('error', 'Product not found in archive');
            }

            // Restore the product
            $product->restore();

            return redirect()->route('product.archive')
                ->with('success', 'Product restored successfully');
        } catch (\Exception $e) {
            return redirect()->route('product.archive')
                ->with('error', 'Error restoring product: '.$e->getMessage());
        }
    }
    
}
