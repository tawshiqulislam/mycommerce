<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LegalPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'content',
        'isOn',
    ];
    
}