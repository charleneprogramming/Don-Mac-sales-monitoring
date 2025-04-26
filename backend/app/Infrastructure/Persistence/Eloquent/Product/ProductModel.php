<?php

namespace App\Infrastructure\Persistence\Eloquent\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model
{
    use SoftDeletes;

    protected $table = 'product';

    protected $fillable = ['id', 'product_id', 'product_image', 'product_name', 'product_price', 'product_stock', 'description', 'userID'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
