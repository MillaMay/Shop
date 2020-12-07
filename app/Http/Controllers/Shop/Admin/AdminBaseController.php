<?php

namespace App\Http\Controllers\Shop\Admin;

use App\Http\Controllers\Shop\BaseController as MainBaseController;
use Illuminate\Http\Request;

abstract class AdminBaseController extends MainBaseController
{
    public function __construct()
    {
        $this->middleware('auth'); // Проверка аутентификации
        $this->middleware('status'); // Проверка статуса пользователя
    }
}
