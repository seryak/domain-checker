<?php

namespace App\Http\Middleware;

use Closure;
use Native\Laravel\Facades\Settings;

class SetLanguage
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
        // Получаем выбранный язык из настроек
        $language = Settings::get('app_language', 'en');
        
        // Устанавливаем локаль приложения
        app()->setLocale($language);
        
        return $next($request);
    }
}