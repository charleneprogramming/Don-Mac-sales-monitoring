<?php

namespace App\Application\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepository;

class RegisterProducts
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(
        string $product_id,
        string $product_name,
        float $product_price,
        string $product_image,
        string $product_stock,
        string $description,
        int $userID,
    ): void {
        // dd($product_price);
        $data = new Product(
            null,
            $product_id,
            $product_image,
            $product_name,
            $product_price,
            $description,
            $product_stock,
            $userID,
        );
        $this->productRepository->create($data);
    }

    public function update(
        string $product_id,
        string $product_name,
        float $product_price,
        string $product_image,
        int $product_stock,
        string $description,
        int $userID
    ) {

        $productModel = $this->productRepository->findByProductID($product_id);
        // dd($product_stock);
        if (! $productModel) {
            return response()->json(['message' => 'Product not found.']);
        }
        $data = new Product(
            null,
            $product_id,
            $product_image,
            $product_name,
            $product_price,
            $description,
            $product_stock,
            $userID,
        );
        $this->productRepository->update($data);
    }

    public function findByProductID(string $product_id)
    {
        return $this->productRepository->findByProductID($product_id);
    }

    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    public function delete(string $product_id)
    {
        return $this->productRepository->delete($product_id);
    }

    public function findByUserID(int $userID): array
    {
        return $this->productRepository->findByUserID($userID);
    }
    public function findByProductNameAndUserID(string $product_name, int $userID): ?Product
    {
        return $this->productRepository->findByProductNameAndUserID($product_name, $userID);
    }
}

//     public function execute($data)
//     {
//         // Temporary implementation for testing
//         return true;
//     }

//     public function findById($id)
//     {
//         // Temporary implementation for testing
//         return (object) [
//             'id' => $id,
//             'name' => 'Test Product',
//             'description' => 'Test Description',
//             'price' => 100,
//             'stock' => 10,
//             'category' => 'Test Category',
//         ];
//     }

//     public function update($id, $data)
//     {
//         // Temporary implementation for testing
//         return true;
//     }

//     public function findAll(): array
//     {
//         return $this->productRepository->findAll();
//     }
// }
