<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 21.10.2020
 * Time: 0:03
 */

namespace App\Services;

use App\Models\Admin\Currency;
use Carbon\Carbon;

class CurrencyConversion
{
    protected static $container; // Чтобы уменьшить запросы к currencies

    public static function loadContainer() // Чтобы уменьшить запросы к currencies
    {
        if (is_null(self::$container)) {
            $currencies = Currency::get();
            foreach ($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }
    /* Этот метод можно будет вызвать вместо $currency_all в app_shop.blade, если будут перечисляться все валюты из базы,
    а не только рубль и доллар, как в $currency_all */
    public static function getCurrencies() // Чтобы уменьшить запросы к currencies для app_shop.blade
    {
        self::loadContainer();
        return self::$container;
    }

    public static function convert($sum, $targetCurrencyCode = null)
    {
        self::loadContainer();

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', 'USD');
        }
        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($targetCurrency->value != 0 || $targetCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
            CurrencyValue::getValue();
            self::loadContainer();
            $targetCurrency = self::$container[$targetCurrencyCode];
        }

        return $sum * $targetCurrency->value;
    }

    public static function getCurrencyCode()
    {
        self::loadContainer();
        $currencyFromSession = session('currency', 'USD');
        $currency = self::$container[$currencyFromSession];

        return $currency->code;
    }

    public static function getCurrencyBase()
    {
        self::loadContainer();
        foreach (self::$container as $code => $currency) {
            if ($currency->isBase()) {
                return $currency;
            }
        }
    }
}