<?php

namespace App\Domain\Product;

/**
 * Data caps ang mga names sa folder tanan.
 * **/
class Product
{
    private ?int $id;

    private ?string $product_id;

    private ?string $product_image;

    private ?string $product_name;

    private ?float $product_price;

    private ?string $description;

    private ?int $product_stock;

    private ?int $userID;

    public function __construct(
        ?int $id = null,
        ?string $product_id = null,
        ?string $product_image = null,
        ?string $product_name = null,
        ?float $product_price = null,
        ?string $description = null,
        ?int $product_stock = null,
        ?int $userID = null,
    ) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->product_image = $product_image;
        $this->product_name = $product_name;
        $this->product_price = $product_price;
        $this->description = $description;
        $this->product_stock = $product_stock;
        $this->userID = $userID;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_image' => $this->product_image,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'description' => $this->description,
            'product_stock' => $this->product_stock,
            'userID' => $this->userID,
        ];
    }

    public function getID(): ?int
    {
        return $this->id;
    }

    public function getProduct_id()
    {
        return $this->product_id;
    }

    public function getProduct_image()
    {
        return $this->product_image;
    }

    public function getProduct_name()
    {
        return $this->product_name;
    }

    public function getProduct_price()
    {
        return $this->product_price;
    }

    public function getProduct_stock()
    {
        return $this->product_stock;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUserID()
    {
        return $this->userID;
    }
}
