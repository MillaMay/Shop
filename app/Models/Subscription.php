<?php

namespace App\Models;

use App\Mail\SendSubscriptionMessage;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Subscription extends Model
{
    protected $fillable = ['email', 'product_id'];

    public function scopeActiveProduct($query, $product_id)
    {
        return $query->where([['status', 0], ['product_id', $product_id]]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function sendEmailSubscription(Product $product) // Передается в AdminProductObserver в updating()
    {
        $subscriptions = self::activeProduct($product->id)->get();
        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->email)->send(new SendSubscriptionMessage($product));
            $subscription->status = 1;
            $subscription->save(); // Если 'status' обновлять через update(), то 'status' надо указывать в массиве $fillable
        }
    }
}
