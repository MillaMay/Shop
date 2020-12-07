<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 24.10.2020
 * Time: 10:18
 */

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class CurrencyValue
{
    public static function getValue()
    {
        $baseCurrency = CurrencyConversion::getCurrencyBase();
        $url = config('currency-value.api_url') . '?base=' . $baseCurrency->code;
        //dd($url);

        $client = new Client();
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() !== 200) { // встроенный метод
            throw new Exception('There is a problem with currency rate service'); // rate - у меня value
        }
        $values = json_decode($response->getBody()->getContents(), true)['rates']; // встроенный методы
        //dd($values); // ответ в json формате, поэтому json_decode, true указан, чтобы получить в виде массива
        foreach (CurrencyConversion::getCurrencies() as $currency) {
            if (!$currency->isBase()) {
                if (!isset($values[$currency->code])) {
                    //throw new Exception('There is a problem with currency ' . $currency->code);
                    // Убрала исключение, потому что парсю с ЕвропаЦБ, а у них нет курса гривны
                    continue;
                } else {
                    $currency->update(['value' => $values[$currency->code]]);
                }
            }
        }
    }
}