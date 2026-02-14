<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion_product extends Model
{
    use HasFactory;

    protected $table = 'promotion_products';

    protected $fillable = ['promotion_id', 'product_id'];


    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product');
    }
}
