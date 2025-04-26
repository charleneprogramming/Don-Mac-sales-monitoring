<?php

namespace App\Application\Sales;

use App\Domain\Sales\Sales;
use App\Domain\Sales\SalesRepository;

class RegisterSales
{
    private SalesRepository $salesRepository;

    public function __construct(SalesRepository $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    public function create(Sales $sales)
    {
        $this->salesRepository->create($sales);
    }

    public function findAll(): array
    {
        return $this->salesRepository->findAll();
    }

    public function findByUserID(int $user_id): array
    {
        return $this->salesRepository->findByUserID($user_id);
    }
}
