<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;
// n
    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'image', 'country', 'status', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $guarded = ['id'];

    protected $appends = ['image_url'];
    protected function generateImageUrl($imageName)
    {
        if (!empty($imageName)) {
            return asset('uploads/customers/' . rawurlencode($imageName));
        } else {
            return null;
        }
    }
    public function getImageUrlAttribute()
    {
        return $this->generateImageUrl($this->image);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function createdBy ()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

}
