<?php

namespace App\Http\Controllers\Shop\Admin;

use App\Http\Requests\AdminCurrencyAddRequest;
use App\Models\Admin\Currency;
use App\Repositories\Admin\CurrencyRepository;
use MetaTag;

class CurrencyController extends AdminBaseController
{
    private $currencyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->currencyRepository = app(CurrencyRepository::class);
    }

    public function index()
    {
        $currency = $this->currencyRepository->getAllCurrency();

        MetaTag::setTags(['title' => 'Список валют']);
        return view('shop.admin.currency.index', compact('currency'));
    }
     public function add(AdminCurrencyAddRequest $request)
     {
         if ($request->isMethod('post')) {
             $data = $request->input();
             $currency = (new Currency())->create($data);
             if ($request->base == 'on') {
                 $this->currencyRepository->switchBaseCurrency();
                 $currency->base = '1';
             }
             $currency->save();
             if ($currency) {
                 return redirect('/admin/currency/index')
                     ->with(['success' => 'Успешно добавлено!']);
             } else {
                 return back()
                     ->withErrors(['msg' => 'Ошибка добавления'])->withInput();
             }
         } else {
             if ($request->isMethod('get')) {
                 MetaTag::setTags(['title' => 'Добавление валюты']);
                 return view('shop.admin.currency.add');
             }
         }
     }

     public function edit(AdminCurrencyAddRequest $request, $id)
     {
         if (empty($id)) {
             return back()->withErrors(['msg' => "Валюта не найдена"]);
         }
         if ($request->isMethod('post')) {
             $currency = Currency::find($id);
             $currency->title = $request->title;
             $currency->code = $request->code;
             $currency->symbol_left = $request->symbol_left;
             $currency->symbol_right = $request->symbol_right;
             $currency->value = $request->value;
             if ($request->base == 'on') {
                 $this->currencyRepository->switchBaseCurrency();
                 $currency->base = '1';
             } else {
                 $currency->base = '0';
             }
             $currency->save();
             if ($currency) {
                 return redirect(url('/admin/currency/edit', $id))
                     ->with(['success' => 'Успешно сохранено!']);
             } else {
                 return back()
                     ->withErrors(['msg' => 'Ошибка добавления'])->withInput();
             }
         } else {
             if ($request->isMethod('get')) {
                 $currency = $this->currencyRepository->getInfoCurrency($id);
                 if (empty($currency)) {
                     abort(404);
                 }
                 MetaTag::setTags(['title' => "Валюта id [$id]"]);
                 return view('shop.admin.currency.edit', compact('currency'));
             }
         }
     }

     public function delete($id)
     {
         if (empty($id)) {
             return back()->withErrors(['msg' => "Валюта не найдена"]);
         }

         $delete = $this->currencyRepository->deleteCurrency($id);
         if ($delete) {
             return back()
                 ->with(['success' => "Валюта id [$id] успешно удалена!"]);
         } else {
             return back()
                 ->withErrors(['msg' => 'Ошибка удаления']);
         }
     }
}