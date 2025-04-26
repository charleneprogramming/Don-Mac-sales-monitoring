<?php

namespace App\Infrastructure\Persistence\Eloquent\Sales;

use App\Domain\Sales\Sales;
use App\Domain\Sales\SalesRepository;

class EloquentSalesRepository implements SalesRepository
{
    public function create(Sales $sales): void
    {
        $saleModel = new SalesModel;
        $saleModel->order_list = $sales->getOrder_list();
        $saleModel->total_order = $sales->getTotal_order();
        $saleModel->quantity = $sales->getQuantity();
        $saleModel->user_id = $sales->getUser_id();
        $saleModel->save();
    }

    public function findAll(): array
    {
        return SalesModel::all()->toArray();
    }

    public function findByUserID(int $user_id): array
    {
        return SalesModel::where('user_id', $user_id)->get()->toArray();
    }
}
