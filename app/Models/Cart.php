<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';

    protected $fillable = [
    'product_id', 'customer_id', 'start_date', 'end_date', 'qty', 'price', 'total', 'discount', 'grand_total',
];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function setTotalAttribute()
    {
        $this->attributes['total'] = $this->qty * $this->price;
    }
}
