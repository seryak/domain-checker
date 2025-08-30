@extends('layouts.app')

@section('title', __('settings.title'))

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title mb-6">{{ __('settings.app_settings') }}</h2>
        
        @if (session('success'))
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">{{ __('settings.theme_label') }}</span>
                    <span class="label-text-alt text-error">*</span>
                </label>
                @php
                    $currentTheme = Settings::get('app_theme', 'light');
                @endphp
                <select name="theme"
                        id="theme"
                        class="select select-bordered w-full"
                        required>
                    <option value="light" {{ $currentTheme === 'light' ? 'selected' : '' }}>{{ __('theme.light') }}</option>
                    <option value="dark" {{ $currentTheme === 'dark' ? 'selected' : '' }}>{{ __('theme.dark') }}</option>
                </select>
                <label class="label">
                    <span class="label-text-alt">{{ __('theme.help') }}</span>
                </label>
                @error('theme')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">{{ __('settings.language_label') }}</span>
                    <span class="label-text-alt text-error">*</span>
                </label>
                <select name="language"
                        id="language"
                        class="select select-bordered w-full"
                        required>
                    <option value="en" {{ $currentLanguage === 'en' ? 'selected' : '' }}>{{ __('language.en') }}</option>
                    <option value="de" {{ $currentLanguage === 'de' ? 'selected' : '' }}>{{ __('language.de') }}</option>
                    <option value="fr" {{ $currentLanguage === 'fr' ? 'selected' : '' }}>{{ __('language.fr') }}</option>
                    <option value="zh" {{ $currentLanguage === 'zh' ? 'selected' : '' }}>{{ __('language.zh') }}</option>
                    <option value="ja" {{ $currentLanguage === 'ja' ? 'selected' : '' }}>{{ __('language.ja') }}</option>
                    <option value="ru" {{ $currentLanguage === 'ru' ? 'selected' : '' }}>{{ __('language.ru') }}</option>
                    <option value="es" {{ $currentLanguage === 'es' ? 'selected' : '' }}>{{ __('language.es') }}</option>
                    <option value="it" {{ $currentLanguage === 'it' ? 'selected' : '' }}>{{ __('language.it') }}</option>
                    <option value="pt" {{ $currentLanguage === 'pt' ? 'selected' : '' }}>{{ __('language.pt') }}</option>
                    <option value="tr" {{ $currentLanguage === 'tr' ? 'selected' : '' }}>{{ __('language.tr') }}</option>
                    <option value="uk" {{ $currentLanguage === 'uk' ? 'selected' : '' }}>{{ __('language.uk') }}</option>
                    <option value="sr" {{ $currentLanguage === 'sr' ? 'selected' : '' }}>{{ __('language.sr') }}</option>
                </select>
                <label class="label">
                    <span class="label-text-alt">{{ __('language.help') }}</span>
                </label>
                @error('language')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">{{ __('settings.stats_label') }}</span>
                </label>
                @php
                    $anonymousStatsEnabled = Settings::get('anonymous_statistics', false);
                @endphp
                <input type="checkbox"
                       name="anonymous_statistics"
                       value="1"
                       {{ $anonymousStatsEnabled ? 'checked' : '' }}
                       class="checkbox checkbox-primary" />
                <label class="label">
                    <span class="label-text-alt">{{ __('stats.help') }}</span>
                </label>
                @error('anonymous_statistics')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('ssl.report') }}" 
                   class="btn btn-ghost">
                    {{ __('button.cancel') }}
                </a>
                <button type="submit"
                         class="btn btn-primary">
                     {{ __('button.save') }}
                 </button>
            </div>
        </form>
    </div>
</div>
@endsection