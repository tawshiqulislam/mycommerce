<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_name',
        'seller_phone',
        'total',
        'buyer_phone',
    ];

    public function pos_order_products()
    {
        return $this->hasMany(PosOrderProduct::class);
    }
}
