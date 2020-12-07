<?php
/**
 * Created by PhpStorm.
 * User: Eva
 * Date: 20.07.2020
 * Time: 16:19
 */

namespace App\Repositories\Admin;

use App\Models\Admin\Currency;
use App\Repositories\CoreRepository;
use App\Models\Admin\Currency as Model;

class CurrencyRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAllCurrency()
    {
        $currency = $this->startConditions()::all();
        return $currency;
    }

    /* Для изменения базовой валюты */
    public function switchBaseCurrency()
    {
        $id = \DB::table('currencies')
            ->where('base', '=', '1')->get()->first();
        if ($id != null) {
            $id = $id->id;
            $new = Currency::find($id);
            $new->base = '0';
            $new->save();
        }
    }

    public  function getInfoCurrency($id)
    {
        $currency = $this->startConditions()->find($id);
        return $currency;
    }

    public function deleteCurrency($id)
    {
        $delete = $this->startConditions()->where('id', $id)->forceDelete();
        return $delete;
    }
}