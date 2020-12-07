<?php

namespace App\Observers;

use App\Models\Admin\Product;
//use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Support\Carbon;

class AdminProductObserver
{
    public function creating(Product $product)
    {
        $this->setAlias($product);
    }

    public function setAlias(Product $product)
    {
        if (empty($product->alias)) {
            $product->alias = \Str::slug($product->title, '-'); // Делиметр для названия из больше, чем одно слово

            // Дальше, чтобы алиас не повторялся
            $check = Product::where('alias', '=', $product->alias)->exists();
            if ($check) {
                $product->alias = \Str::slug($product->title) . \Str::random(3); // Подставляет 3 рандомные буквы
            }
        }
    }

    public function saving(Product $product)
    {
        $this->setPublishedAt($product);
    }

    public function setPublishedAt(Product $product)
    {
        $setPublished = empty($product->updated_at) || !empty($product->updated_at);
        if ($setPublished) {
            $product->updated_at = Carbon::now(); // Установит время апдейта (внимание на use Карбона вверху)
        }
    }

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    // For Subscription
    public function updating(Product $product)
    {
        // Узнать предыдущий count:
        $oldCount = $product->getOriginal('count');
        // dd($oldCount);

        if ($oldCount == 0 && $product->count > 0) {
            Subscription::sendEmailSubscription($product);
        }
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Models\Admin\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
