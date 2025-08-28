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
            'language' => 'required|string|in:en,de,fr,zh,ja,ru,es,it,pt,tr,uk,sr',
            'theme' => 'required|string|in:light,dark'
        ]);

        // Сохранение выбранного языка в настройках
        Settings::set('app_language', $request->language);

        // Сохранение выбранной темы в настройках
        Settings::set('app_theme', $request->theme);

        // Возвращаем JSON ответ для AJAX запросов
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Настройки успешно сохранены!',
                'theme' => $request->theme
            ]);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Настройки успешно сохранены!');
    }
}