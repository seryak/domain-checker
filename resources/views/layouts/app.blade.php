<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen flex flex-col bg-base-100">
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 border-r border-base-300 flex flex-col">
            <div class="p-4 border-b border-base-300">
                <img src="/logo_{{ Settings::get('app_theme', 'light') }}.png" alt="SSLPatrol Logo" class="h-16 w-auto mb-2" id="app-logo">
            </div>
            
            <nav class="flex-1 p-4">
                <ul class="menu menu-vertical gap-1">
                    <li class="menu-title">
                        <span>{{ __('nav.section.main') }}</span>
                    </li>
{{--                    <li>--}}
{{--                        <a href="{{ url('/') }}" @if(request()->is('/')) class="active" @endif>--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />--}}
{{--                            </svg>--}}
{{--                            Главная--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li>
                        <a href="{{ route('ssl.report') }}" @if(request()->routeIs('ssl.report')) class="active" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('nav.ssl_report') }}
                        </a>
                    </li>
                    
                    <li class="menu-title mt-4">
                        <span>{{ __('nav.section.demos') }}</span>
                    </li>
                    <li>

                    </li>
                    
                    <li class="menu-title mt-4">
                        <span>{{ __('nav.settings') }}</span>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" @if(request()->is('settings*')) class="active" @endif>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('nav.settings') }}
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="p-4 border-t border-base-300">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-10">
                            <span>{{ auth()->user()->name ?? 'U' }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-medium">{{ auth()->user()->name ?? __('user.default_name') }}</div>
                        <div class="text-sm opacity-70">{{ __('user.role.admin') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
{{--            <div class="bg-base-100 border-b border-base-300 p-4">--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    <div class="text-xl font-semibold">@yield('title', 'Панель управления')</div>--}}
{{--                    <div class="flex items-center gap-2">--}}
{{--                        <button class="btn btn-ghost btn-sm">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                        <div class="dropdown dropdown-end">--}}
{{--                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">--}}
{{--                                <div class="w-10 rounded-full">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <ul tabindex="0" class="menu dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">--}}
{{--                                <li><a>Профиль</a></li>--}}
{{--                                <li><a>Настройки</a></li>--}}
{{--                                <li><a>Выйти</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Content Area -->
            <div class="flex-1 overflow-auto p-6">
                @yield('content')
            </div>
        </div>
    </div>

    @yield('scripts')
    <script>
        // Initialize theme when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.documentElement.setAttribute('data-theme', '{{ Settings::get('app_theme', 'light') }}');
        });
    </script>
    @if(Settings::get('anonymous_statistics', false))
    <script>
        <!-- Matomo -->
            var _paq = window._paq = window._paq || [];
            /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
            var u="//65.21.152.121:8085/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    <!-- End Matomo Code -->

    </script>
    @endif
</body>
</html>