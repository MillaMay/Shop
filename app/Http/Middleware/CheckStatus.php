<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckStatus // Указан в Kernel.php
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->isAdministrator()) {
            // For kcfinder/conf/config.php
            session_start();
            $_SESSION['admin'] = 'admin';
            // -end------------------------
            return $next($request);
        } else {
            unset($_SESSION['admin']);
            return redirect('/');
        }
    }
}
