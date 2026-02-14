<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion_category extends Model
{
    use HasFactory;

    protected $table = 'promotion_categories';

    protected $fillable = ['promotion_id', 'category_id'];
    public function getNameAttribute($name)
{
    // If it's an admin page, return the default name
    if (strpos(url()->current(), '/admin')) {
        return $name;
    }

    // Find the translation for the current locale
    $translation = $this->translations->where('locale', App::getLocale())->first();

    // Return the translated name if it exists, otherwise return the original name
    return $translation ? $translation->value : $name;
}

}
