<?php

namespace App\Domain\Product;

interface ProductRepository
{
    public function findAll(): array;

    public function create(Product $product): void;

    public function update(Product $product): void;

    public function delete(string $id): void;

    public function findById(int $id): ?Product;

    public function findByProductID(string $product_id): ?Product;

    public function findByUserID(int $userID): array;

    public function findByProductNameAndUserID(string $product_name, int $userID): ?Product;

    // public function create(array $data): void;

    // public function update(int $id, array $data): ?Product;

    // public function delete(int $id): void;
}
