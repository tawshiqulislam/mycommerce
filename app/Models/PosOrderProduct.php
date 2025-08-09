<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'quantity',
        'price',
        'total',
        'pos_order_id',
    ];

    public function pos_order()
    {
        return $this->belongsTo(PosOrder::class);
    }
}
