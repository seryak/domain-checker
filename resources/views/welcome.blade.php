@extends('layouts.app')

@section('title', __('welcome.title'))

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title">{{ __('welcome.greeting') }}</h2>
        <p>{{ __('welcome.description') }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="card bg-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        {{ __('feature.docs.title') }}
                    </h3>
                    <p>{{ __('feature.docs.description') }}</p>
                    <div class="card-actions justify-end mt-2">
                        <a href="https://laravel.com/docs" target="_blank" class="btn btn-primary btn-sm">
                            {{ __('button.visit') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card bg-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        {{ __('feature.videos.title') }}
                    </h3>
                    <p>{{ __('feature.videos.description') }}</p>
                    <div class="card-actions justify-end mt-2">
                        <a href="https://laracasts.com" target="_blank" class="btn btn-primary btn-sm">
                            {{ __('button.visit') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="https://cloud.laravel.com" target="_blank" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4 4 0 003 15z" />
                </svg>
                {{ __('feature.deploy.title') }}
            </a>
        </div>
    </div>
</div>
@endsection
