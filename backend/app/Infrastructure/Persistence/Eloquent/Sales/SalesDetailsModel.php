<?php

namespace App\Infrastructure\Persistence\Eloquent\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetailsModel extends Model
{
    use SoftDeletes;

    protected $table = 'sales_details';

    protected $fillable = [
        'sales_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    public function sale()
    {
        return $this->belongsTo(SalesModel::class, 'sales_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Infrastructure\Persistence\Eloquent\Product\ProductModel', 'product_id', 'product_id');
    }
}