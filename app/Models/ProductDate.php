<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDate extends Model
{
    use HasFactory;
    protected $table = 'product_dates';
    protected $fillable = [
        'product_id',
        'start_date',
        'end_date',
        'is_active',
        'number',
        'price',
    ];
}
