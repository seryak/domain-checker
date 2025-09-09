<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Native\Laravel\Facades\Settings;

class CheckTermsAgreed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user has agreed to terms
        $termsAccepted = Settings::get('terms_accepted', false);

        // Allow access to welcome and analytics routes
        if ($request->is('welcome*') ||
            $request->is('analytics-consent*') ||
            $request->is('api/*')) {
            return $next($request);
        }

        // If terms not accepted, redirect to welcome
        if (!$termsAccepted) {
            return redirect()->route('welcome');
        }

        return $next($request);
    }
}