<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'promotion_type',
        'start_date',
        'end_date',
        'image',
        'default_key'
    ];

    // Define many-to-many relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category');
    }
}
