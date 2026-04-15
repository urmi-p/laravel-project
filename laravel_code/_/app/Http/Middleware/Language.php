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
      // Priority:
      // 1) Logged-in user's saved language
      // 2) Guest/session-selected language
      // 3) Admin default locale
      if (auth()->check() && auth()->user()->language != '') {
        $locale = auth()->user()->language;
        // Keep session locale in sync so Helpers/controllers using session('locale')
        // resolve translated content (like custom pages in footer) correctly.
        Session::put('locale', $locale);
      } elseif (Session::has('locale') && session('locale') != '') {
        $locale = session('locale');
      } else {
        $locale = config('app.locale');
        Session::put('locale', $locale);
      }

      app()->setLocale($locale);

      return $next($request);
    }
}
