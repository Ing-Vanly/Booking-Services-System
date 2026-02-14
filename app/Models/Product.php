<?php

namespace App\Models;

use App\helpers\AppHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\helpers\GlobalFunction;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function getNameAttribute($name)
    {
        if (strpos(url()->current(), '/admin')) {
            return $name;
        }
        return $this->translations[0]->value ?? $name;
    }
    public function getDescriptionAttribute($description)
    {
        if (strpos(url()->current(), '/admin')) {
            return $description;
        }
        return $this->translations[1]->value ?? $description;
    }


    protected $appends = ['image_url'];
    protected function generateImageUrl($imageName)
    {
        if (!empty($imageName)) {
            return asset('uploads/products/' . rawurlencode($imageName));
        } else {
            return null;
        }
    }
    public function getImageUrlAttribute()
    {
        return $this->generateImageUrl($this->image);
    }

    public function transaction_selllines()
    {
        // Adjust the foreign key if needed
        return $this->hasMany(TransactionSellline::class, 'product_id');
    }




    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brandCategory()
    {
        return $this->belongsTo(BrandCategory::class, 'brand_category_id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')) {
                    return $query->where('locale', App::getLocale());
                } else {
                    return $query->where('locale', AppHelper::default_lang());
                }
            }]);
        });
    }

    //  relationship wiht promotion

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function getDatesInRange($start_date,$end_date)
    {
        $query = ProductDate::query();
        $query->where('product_id', $this->id);
        // dd($query->pluck('start_date'));
        $query->where('start_date','>=',date('Y-m-d H:i:s',strtotime($start_date)));
        $query->where('end_date','<=',date('Y-m-d H:i:s',strtotime($end_date)));

        return $query->take(100)->get();
    }

    public function isAvailableAt($filters = [])
    {
        // dd($filters);
        if(empty($filters['start_date']) or empty($filters['end_date'])) return true;

        $filters['end_date'] = date("Y-m-d", strtotime($filters['end_date']));
        // dd($filters);

        $productDates =  $this->getDatesInRange($filters['start_date'],$filters['end_date']);
        // dd($productDates);
        $allDates = [];
        $tmp_price = 0;
        $tmp_night = 0;
        $period = GlobalFunction::periodDate($filters['start_date'],$filters['end_date'],true);
        // dd($period);
        foreach ($period as $dt){
            $allDates[$dt->format('Y-m-d')] = [
                'number'=>$this->number,
                // 'price'=>$this->price
            ];
            $tmp_night++;
        }
        // dd($allDates);
        if($productDates->count() > 0 && !empty($productDates))
        {
            foreach ($productDates as $row)
            {
                if(!$row->is_active or !$row->number) return false;

                if(!array_key_exists(date('Y-m-d',strtotime($row->start_date)),$allDates)) continue;

                $allDates[date('Y-m-d',strtotime($row->start_date))] = [
                    'number'=>$row->number
                ];
            }
        }

        $productBookings = $this->getBookingsInRange($filters['start_date'],$filters['end_date']);
        // dd($productBookings);

        if($productBookings->count() > 0 && !empty($productBookings)){
            foreach ($productBookings as $productBooking){
                $period = GlobalFunction::periodDate($productBooking->start_date, false);
                foreach ($period as $dt){
                    $date = $dt->format('Y-m-d');
                    if(!array_key_exists($date,$allDates)) continue;
                    $allDates[$date]['number'] -= 1;
                    if($allDates[$date]['number'] <= 0){
                        return false;
                    }
                }
            }
        }

        // dd($allDates);
        $this->tmp_number = !empty($allDates) ?  (int) min(array_column($allDates,'number')) : 0;
        if(empty($this->tmp_number)) return false;

        // //Adult - Children
        // if( !empty($filters['adults']) and $this->adult  < $filters['adults'] ){
        //     return false;
        // }
        // if( !empty($filters['children']) and $this->child  < $filters['children'] ){
        //     return false;
        // }

        $this->tmp_price = array_sum(array_column($allDates,'price'));
        $this->tmp_dates = $allDates;
        $this->tmp_nights = $tmp_night;

        return true;
    }
    public function getBookingsInRange($start_date, $end_date)
    {
        // dd($start_date, $end_date);
        return Transaction::where('start_date', '>=', date('Y-m-d H:i:s', strtotime($start_date)))
            ->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($end_date)))
            ->get();
    }
}
