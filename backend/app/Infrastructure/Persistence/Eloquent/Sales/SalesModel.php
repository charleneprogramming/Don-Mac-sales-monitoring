<?php

namespace App\Infrastructure\Persistence\Eloquent\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesModel extends Model
{
    use SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'user_id',
        'total_order',
        'quantity',
        'delivery_method',
        'merchant_fee',
        'status'
    ];

    protected $casts = [
        'total_order' => 'decimal:2',
        'quantity' => 'integer',
        'delivery_method' => 'boolean',
        'merchant_fee' => 'decimal:2',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\Infrastructure\Persistence\Eloquent\User\UserModel', 'user_id');
    }

    public function salesDetails()
    {
        return $this->hasMany('App\Infrastructure\Persistence\Eloquent\Sales\SalesDetailsModel', 'sales_id');
    }
}
