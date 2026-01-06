<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgeVerificationStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check()
            && config('settings.age_verification_status')
            && $request->user()->age_verification !== 1
            && $request->user()->role != 'admin'
            && $request->route()->getName() != 'verify.age'
            && $request->route()->getName() != 'age.start'
            && $request->route()->getName() != 'age.verification.result'
            && !$request->is('logout')
        ) {

            if ($request->ajax() && $request->is('search/creators')) {
                return response()->json([
                    'status' => false
                ]);
            }

            return redirect()->route('verify.age');
        }

        return $next($request);
    }
}
