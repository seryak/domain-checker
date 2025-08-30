@extends('layouts.app')

@section('title', __('domain.create.title'))

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title mb-6">{{ __('domain.create.title') }}</h2>
        
        @if (session('error'))
            <div class="alert alert-error mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <form action="{{ route('domains.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">{{ __('domain.name_label') }}</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       placeholder="example.com"
                       class="input input-bordered w-full"
                       value="{{ old('name') }}"
                       required />
                @error('name')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
                <label class="label">
                    <span class="label-text-alt">{{ __('domain.name_help') }}</span>
                </label>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">{{ __('domain.ssl_port_label') }}</span>
                </label>
                <input type="number"
                       name="ssl_port"
                       id="ssl_port"
                       min="1"
                       max="65535"
                       placeholder="443"
                       class="input input-bordered w-full"
                       value="{{ old('ssl_port', 443) }}"
                       title="{{ __('domain.ssl_port_tooltip') }}" />
                @error('ssl_port')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
                <label class="label">
                    <span class="label-text-alt">{{ __('domain.ssl_port_help') }}</span>
                </label>
            </div>

            <div class="form-control">
                <label class="label cursor-pointer justify-start gap-3">
                    <input type="checkbox" name="check_ssl" class="checkbox" checked />
                    <span class="label-text">{{ __('ssl.check_label') }}</span>
                </label>
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('ssl.report') }}" 
                   class="btn btn-ghost">
                    {{ __('button.cancel') }}
                </a>
                <button type="submit" 
                        class="btn btn-primary">
                    <span class="spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span class="button-text">{{ __('button.add_domain') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner');
        const buttonText = submitBtn.querySelector('.button-text');
        
        document.querySelector('form').addEventListener('submit', function() {
            // Disable button and show spinner
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
        });
    });
</script>
@endsection