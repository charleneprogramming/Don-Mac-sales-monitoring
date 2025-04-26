<?php

namespace App\Infrastructure\Persistence\Eloquent\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepository;

class EloquentProductRepository implements ProductRepository
{
    public function findAll(): array
    {
        $products = ProductModel::whereNull('deleted_at')->get();

        return $products->map(function ($productModel) {
            return new Product(
                $productModel->id,
                $productModel->product_id,
                $productModel->product_image,
                $productModel->product_name,
                (float) $productModel->product_price,
                $productModel->description,
                (int) $productModel->product_stock,
                (int) $productModel->userID
            );
        })->all();
    }

    public function create(Product $product): void
    {
        $productModel = ProductModel::find(id: $product->getID()) ?? new ProductModel;
        $productModel->id = $product->getID();
        $productModel->product_image = $product->getProduct_image();
        $productModel->product_id = $product->getProduct_id();
        $productModel->product_name = $product->getProduct_name();
        $productModel->product_price = $product->getProduct_price();
        $productModel->product_stock = $product->getProduct_stock();
        $productModel->description = $product->getDescription();
        $productModel->userID = $product->getUserID();
        $productModel->save();
    }

    public function update(Product $product): void
    {
        // dd($product);
        $existingProduct = ProductModel::where(column: 'product_id', operator: $product->getProduct_id())->first();
        if ($existingProduct) {
            $existingProduct->product_name = $product->getProduct_name();
            $existingProduct->product_image = $product->getProduct_image();
            $existingProduct->product_price = $product->getProduct_price();
            $existingProduct->product_stock = $product->getProduct_stock();
            $existingProduct->description = $product->getDescription();
            $existingProduct->userID = $product->getUserID();
            $existingProduct->save();
        } else {
            $productModel = new ProductModel;
            $productModel->id = $product->getID();
            $productModel->product_id = $product->getProduct_id();
            $productModel->product_name = $product->getProduct_name();
            $productModel->product_image = $product->getProduct_image();
            $productModel->product_price = $product->getproduct_price();
            $productModel->product_stock = $product->getProduct_stock();
            $productModel->description = $product->getDescription();
            $productModel->userID = $product->getUserID();
            $productModel->save();
        }

    }

    public function findById(int $id): ?Product
    {
        $productModel = ProductModel::where('id', $id)->first();
        if (! $productModel) {
            return null;
        }

        return new Product(
            $productModel->id,
            $productModel->product_id,
            $productModel->product_name,
            $productModel->product_image,
            $productModel->product_price,
            $productModel->product_stock,
            $productModel->description,
            $productModel->userID
        );
    }

    public function findByProductID(string $product_id): ?Product
    {
        $productModel = ProductModel::where('product_id', $product_id)->first();
        if (! $productModel) {
            return null;
        }

        return new Product(
            $productModel->id,
            $productModel->product_id,
            $productModel->product_image,
            $productModel->product_name,
            $productModel->product_price,
            $productModel->description,
            $productModel->product_stock,
            $productModel->userID
        );
    }

    public function delete(string $product_id): void
    {
        ProductModel::where('product_id', $product_id)->delete();
    }

    public function findByUserID(int $userID): array
    {
        $products = ProductModel::where('userID', $userID)->whereNull('deleted_at')->get();

        if (count($products) === 0) {
            return [];
        } else {
            // dd($products);

            return $products->map(function ($productModel) {
                return new Product(
                    $productModel->id,
                    $productModel->product_id,
                    $productModel->product_image,
                    $productModel->product_name,
                    (float) $productModel->product_price,
                    $productModel->description,
                    (int) $productModel->product_stock,
                    (int) $productModel->userID
                );
            })->toArray();
        }

    }

    public function findByProductNameAndUserID(string $product_name, int $userID): ?Product
    {
        $productModel = ProductModel::where('product_name', $product_name)->where('userID', $userID)->first();
        if (! $productModel) {
            return null;
        }

        return new Product(
            null,
            $productModel->product_id,
            $productModel->product_image,
            $productModel->product_name,
            $productModel->product_price,
            $productModel->description,
            $productModel->product_stock,
            $productModel->userID
        );
    }

    // public function create(array $data)
    // {
    //     return ProductModel::create($data);
    // }

    // public function update($id, array $data)
    // {
    //     $product = $this->findById($id);
    //     $product->update($data);

    //     return $product;
    // }

}
