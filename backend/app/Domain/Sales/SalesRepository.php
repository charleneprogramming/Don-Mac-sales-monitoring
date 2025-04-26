<?php

namespace App\Domain\Sales;

interface SalesRepository
{
    public function create(Sales $order_list): void;

    public function findALL(): array;

    public function findByUserID(int $user_id): array;
}
