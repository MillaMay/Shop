<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes; // Для мягкого удаления

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'currency',
        'note',
    ];

    // Связь заказа с продуктами (клиентская часть)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('qty');
    }

    public function getFullPrice()
    {
        $sum = 0;
        foreach ($this->products as $product) {
            $sum += $product->getPriceForCount();
        }
        return $sum;
    }
}
