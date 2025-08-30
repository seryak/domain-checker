@extends('layouts.app')

@section('title', 'Настройки')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <h2 class="card-title mb-6">Настройки приложения</h2>
        
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
                    <span class="label-text">Тема приложения</span>
                    <span class="label-text-alt text-error">*</span>
                </label>
                @php
                    $currentTheme = Settings::get('app_theme', 'light');
                @endphp
                <select name="theme"
                        id="theme"
                        class="select select-bordered w-full"
                        required>
                    <option value="light" {{ $currentTheme === 'light' ? 'selected' : '' }}>Светлая</option>
                    <option value="dark" {{ $currentTheme === 'dark' ? 'selected' : '' }}>Темная</option>
                </select>
                <label class="label">
                    <span class="label-text-alt">Выберите цветовую тему интерфейса</span>
                </label>
                @error('theme')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Язык приложения</span>
                    <span class="label-text-alt text-error">*</span>
                </label>
                <select name="language"
                        id="language"
                        class="select select-bordered w-full"
                        required>
                    <option value="en" {{ $currentLanguage === 'en' ? 'selected' : '' }}>Английский</option>
                    <option value="de" {{ $currentLanguage === 'de' ? 'selected' : '' }}>Немецкий</option>
                    <option value="fr" {{ $currentLanguage === 'fr' ? 'selected' : '' }}>Французский</option>
                    <option value="zh" {{ $currentLanguage === 'zh' ? 'selected' : '' }}>Китайский</option>
                    <option value="ja" {{ $currentLanguage === 'ja' ? 'selected' : '' }}>Японский</option>
                    <option value="ru" {{ $currentLanguage === 'ru' ? 'selected' : '' }}>Русский</option>
                    <option value="es" {{ $currentLanguage === 'es' ? 'selected' : '' }}>Испанский</option>
                    <option value="it" {{ $currentLanguage === 'it' ? 'selected' : '' }}>Итальянский</option>
                    <option value="pt" {{ $currentLanguage === 'pt' ? 'selected' : '' }}>Португальский</option>
                    <option value="tr" {{ $currentLanguage === 'tr' ? 'selected' : '' }}>Турецкий</option>
                    <option value="uk" {{ $currentLanguage === 'uk' ? 'selected' : '' }}>Украинский</option>
                    <option value="sr" {{ $currentLanguage === 'sr' ? 'selected' : '' }}>Сербский</option>
                </select>
                <label class="label">
                    <span class="label-text-alt">Выберите язык интерфейса приложения</span>
                </label>
                @error('language')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label class="label cursor-pointer">
                    <span class="label-text">Разрешить сбор анонимной статистики</span>
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
                    <span class="label-text-alt">Сбор анонимной статистики помогает улучшить качество приложения</span>
                </label>
                @error('anonymous_statistics')
                    <span class="text-error text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('ssl.report') }}" 
                   class="btn btn-ghost">
                    Отмена
                </a>
                <button type="submit"
                        class="btn btn-primary">
                    <span class="spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span class="button-text">Сохранить</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Function to update logo based on theme
    function updateLogo(theme) {
        const logoElement = document.getElementById('app-logo');
        if (logoElement) {
            logoElement.src = `/logo_${theme}.png`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner');
        const buttonText = submitBtn.querySelector('.button-text');
        
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button and show spinner
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');
            
            // Submit form using fetch
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success mb-4';
                    successAlert.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>${data.message}</span>
                    `;
                    
                    // Insert success message before form
                    this.parentNode.insertBefore(successAlert, this);
                    
                    // Update language immediately
                    const selectedLanguage = document.getElementById('language').value;
                    document.documentElement.lang = selectedLanguage;

                    // Update theme immediately
                    const selectedTheme = document.getElementById('theme').value;
                    document.documentElement.setAttribute('data-theme', selectedTheme);

                    // Update logo immediately
                    updateLogo(selectedTheme);

                    // Update page title
                    document.title = `Настройки - ${selectedLanguage.toUpperCase()}`;
                    
                    // Remove old messages
                    const oldMessages = this.parentNode.querySelectorAll('.alert');
                    oldMessages.forEach(msg => {
                        if (msg !== successAlert) {
                            msg.remove();
                        }
                    });
                    
                    // Re-enable button after delay
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        spinner.classList.add('hidden');
                        buttonText.classList.remove('hidden');
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Ошибка сохранения');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-error mb-4';
                errorAlert.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>${error.message}</span>
                `;
                
                // Insert error message before form
                this.parentNode.insertBefore(errorAlert, this);
                
                // Re-enable button
                submitBtn.disabled = false;
                spinner.classList.add('hidden');
                buttonText.classList.remove('hidden');
            });
        });
    });
</script>
@endsection