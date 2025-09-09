@extends('layouts.app')

@section('title', __('analytics.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center p-4">
    <div class="max-w-lg w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-primary mb-6">{{ __('analytics.title') }}</h1>
        </div>

        <!-- Content -->
        <div class="bg-base-100 rounded-lg shadow-xl p-8 space-y-6">
            <!-- Description -->
            <div class="space-y-4">
                <p class="text-base-content leading-relaxed">
                    {{ __('analytics.description') }}
                </p>

                <div class="bg-info/10 border border-info/20 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-info shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-info-content leading-relaxed">
                            {{ __('analytics.disclaimer') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <form action="{{ route('analytics.consent.submit') }}" method="POST" class="space-y-3">
                    @csrf

                    <!-- Allow Button -->
                    <button type="submit"
                            name="consent"
                            value="allow"
                            class="w-full bg-primary hover:bg-primary-focus text-primary-content font-medium py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-focus focus:ring-offset-2">
                        {{ __('analytics.button_allow') }}
                    </button>

                    <!-- Decline Button -->
                    <button type="submit"
                            name="consent"
                            value="decline"
                            class="w-full bg-base-200 hover:bg-base-300 text-base-content font-medium py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-base-content focus:ring-offset-2">
                        {{ __('analytics.button_decline') }}
                    </button>
                </form>
            </div>

            <!-- Skip Option -->
            <div class="text-center pt-4 border-t border-base-200">
                <a href="{{ route('ssl.report') }}"
                   class="text-xs text-base-content/60 hover:text-base-content/80 transition-colors">
                    Пропустить и перейти к приложению
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-base-content/50">
            SSLPatrol v{{ config('nativephp.appVersion', '1.0') }}
        </div>
    </div>
</div>
@endsection