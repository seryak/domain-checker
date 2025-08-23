<?php

namespace App\Http\Controllers;

use Native\Laravel\Facades\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $currentLanguage = Settings::get('app_language', 'en');
        return view('settings.index', compact('currentLanguage'));
    }

    /**
     * Update the settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Валидация формы
        $request->validate([
            'language' => 'required|string|in:en,de,fr,zh,ja,ru,es,it,pt,tr,uk,sr'
        ]);

        // Сохранение выбранного языка в настройках
        Settings::set('app_language', $request->language);
        
        // Возвращаем JSON ответ для AJAX запросов
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Язык успешно изменен!'
            ]);
        }
        
        return redirect()->route('settings.index')
            ->with('success', 'Язык успешно изменен!');
    }
}