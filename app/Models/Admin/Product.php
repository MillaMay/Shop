<?php

namespace App\Models\Admin;

use App\Services\CurrencyConversion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    //protected $table = 'product'; // For error

    protected $fillable = [
        'category_id',
        'brand_id',
        'title',
        'alias',
        'content',
        'price',
        'old_price',
        'status',
        'keywords',
        'description',
        'img',
        'hit',
        'count',
    ];

    public function getPriceForCount() // Для подсчета суммы корзины (и в корзине)
    {
        if (!is_null($this->pivot)) {
            return $this->pivot->qty * $this->price;
        }
        return $this->price;
    }

    public function getPriceAttribute($value) // обращение к price
    {
        return round(CurrencyConversion::convert($value));
    }

    public function getOldPriceAttribute($value) // обращение к old_price
    {
        return round(CurrencyConversion::convert($value));
    }
}
