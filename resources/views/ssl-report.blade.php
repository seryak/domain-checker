@extends('layouts.app')
@php use App\Models\Enum\SslStatus; @endphp
@section('title', 'SSL Отчёт')

@section('content')
<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <h2 class="card-title">SSL Отчёт</h2>
            <div class="flex gap-2">
                <form action="{{ route('ssl.report') }}" method="GET">
                    <button class="btn btn-primary" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Обновить отчет
                    </button>
                </form>
                <button id="runCheckBtn" class="btn btn-secondary">
                    <span class="spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                    <span class="button-text">Execute Check</span>
                </button>
            </div>
        </div>

        <div id="notification-container"></div>

        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-2">
                <input type="text"
                       placeholder="Поиск домена..."
                       class="input input-bordered w-full md:w-64"
                       name="search"
                       value="{{ request('search') }}"
                       form="filterForm">
                
                <select class="select select-bordered" name="status" form="filterForm">
                    <option value="">Все статусы</option>
                    <option value="{{ SslStatus::OK->value }}" @selected(request('status') == SslStatus::OK->value)>Действительные</option>
                    <option value="{{ SslStatus::EXPIRED->value }}" @selected(request('status') == SslStatus::EXPIRED->value)>Истекли</option>
                    <option value="{{ SslStatus::ERROR->value }}" @selected(request('status') == SslStatus::ERROR->value)>Ошибка</option>
                </select>
            </div>
            
            <div class="flex flex-col md:flex-row items-start md:items-center gap-2">
                <span class="text-sm">Сортировка:</span>
                <div class="flex gap-2">
                    <select class="select select-bordered select-sm" name="sort" form="filterForm">
                        <option value="expired" @selected(request('sort') == 'valid_to')>Дата окончания</option>
                        <option value="domain_id" @selected(request('sort') == 'domain_id')>Домен</option>
                    </select>
                    <select class="select select-bordered select-sm" name="direction" form="filterForm">
                        <option value="asc" @selected(request('direction') == 'asc')>По возрастанию</option>
                        <option value="desc" @selected(request('direction') == 'desc')>По убыванию</option>
                    </select>
                </div>
            </div>
        </div>

        <form id="filterForm" action="{{ route('ssl.report') }}" method="GET" class="hidden">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">
        </form>

        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Домен</th>
                        <th>Статус</th>
                        <th>Дата окончания</th>
                        <th>Действия</th>
                        <th>Управление</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($certificates as $cert)
                        <tr>
                            <td>{{ $cert->domain->name }}</td>
                            <td>
                                @switch($cert->status)
                                    @case(SslStatus::OK->value)
                                        <span class="badge badge-success">Действителен</span>
                                        @break
                                    @case(SslStatus::EXPIRED->value)
                                        <span class="badge badge-warning">Истек</span>
                                        @break
                                    @case(SslStatus::ERROR->value)
                                        <span class="badge badge-error">Ошибка</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                {{ $cert->expired?->translatedFormat('d F Y H:i') }}
                                <div class="text-sm opacity-70">({{ $cert->expired?->diffForHumans() }})</div>
                            </td>
                            <td>
                                <a href="https://{{ $cert->domain->name }}"
                                   target="_blank"
                                   class="btn btn-sm btn-ghost">
                                    Перейти
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <!-- Кнопка удаления домена -->
                                    <form action="{{ route('domains.destroy', $cert->domain->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(this.form, 'Домен и все SSL-сертификаты будут удалены. Продолжить?')">
                                            Удалить домен
                                        </button>
                                    </form>
                                    
                                    <!-- Кнопка удаления сертификата -->
                                    @if($cert->id)
                                        <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm"
                                                    onclick="confirmDelete(this.form, 'Удалить SSL-сертификат? Домен останется.')">
                                                Удалить сертификат
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Нет данных для отображения</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $certificates->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const runCheckBtn = document.getElementById('runCheckBtn');
        const spinner = runCheckBtn.querySelector('.spinner');
        const buttonText = runCheckBtn.querySelector('.button-text');
        const notificationContainer = document.getElementById('notification-container');

        runCheckBtn.addEventListener('click', function() {
            // Disable button and show spinner
            runCheckBtn.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');

            // Clear previous notifications
            notificationContainer.innerHTML = '';

            // Make AJAX request
            axios.post('{{ route('ssl.trigger-check') }}')
                .then(function(response) {
                    if (response.data.success) {
                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className = 'alert alert-success mt-3';
                        successAlert.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>${response.data.message}</span>
                        `;
                        notificationContainer.appendChild(successAlert);
                    } else {
                        // Show error message
                        const errorAlert = document.createElement('div');
                        errorAlert.className = 'alert alert-error mt-3';
                        errorAlert.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>${response.data.message}</span>
                        `;
                        notificationContainer.appendChild(errorAlert);
                    }
                })
                .catch(function(error) {
                    // Show error message
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'alert alert-error mt-3';
                    errorAlert.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Error: ${error.message}</span>
                    `;
                    notificationContainer.appendChild(errorAlert);
                    console.error('Error executing domain check:', error);
                })
                .finally(function() {
                    // Re-enable button and hide spinner
                    runCheckBtn.disabled = false;
                    spinner.classList.add('hidden');
                    buttonText.classList.remove('hidden');
                });
        });
    });

    // Функция подтверждения удаления
    function confirmDelete(form, message) {
        if (confirm(message)) {
            form.submit();
        }
    }
</script>
@endsection