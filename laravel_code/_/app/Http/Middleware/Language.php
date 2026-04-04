<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class Language
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
      // User Session Check
      if (auth()->check() && auth()->user()->language != '') {
        app()->setLocale(auth()->user()->language);
        Session::put('locale', auth()->user()->language);
      } else {
        if (Session::has('locale')) {
          app()->setLocale(session('locale'));
        } else {
          $defaultLocale = config('app.locale');

          app()->setLocale($defaultLocale);
          Session::put('locale', $defaultLocale);
        }
      } // User Session Check

      return $next($request);
    }
}
