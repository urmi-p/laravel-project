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
      // Only two options:
      // 1) User-selected language (profile/session)
      // 2) Default locale from env (config('app.locale'))
      if (auth()->check() && auth()->user()->language != '') {
        $locale = auth()->user()->language;
      } elseif (Session::has('locale') && session('locale') != '') {
        $locale = session('locale');
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
