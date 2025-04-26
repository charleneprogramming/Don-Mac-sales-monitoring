<?php

namespace app\Infrastructure\Persistence\Eloquent\Sales;

use Illuminate\Database\Eloquent\Model;

class SalesModel extends Model
{
    protected $table = 'sales';

    protected $fillable = ['order_list', 'total_order', 'quantity', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Infrastructure\Persistence\Eloquent\User\UserModel', 'user_id');
    }
}
