<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ReviewObserver;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'rating',
        'review',
        'featured',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
