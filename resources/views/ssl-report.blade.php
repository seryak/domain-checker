@php use App\Models\Enum\SslStatus; @endphp
        <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSL Отчёт</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100" x-data="{ openFilter: false }">
<div class="container mx-auto px-4 py-8">
    <form action="{{ route('ssl.report') }}" method="GET" class="mb-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">SSL Отчёт</h1>
        <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg">
            Обновить отчет
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <div class="flex space-x-4">
                <input type="text"
                       placeholder="Поиск домена..."
                       class="border rounded-lg px-4 py-2 w-64"
                       name="search"
                       value="{{ request('search') }}">
                <select class="border rounded-lg px-4 py-2" name="status">
                    <option value="">Все статусы</option>
                    <option value="{{ SslStatus::OK->value }}" @selected(request('status') == SslStatus::OK->value)>Действительные</option>
                    <option value="{{ SslStatus::EXPIRED->value }}" @selected(request('status') == SslStatus::EXPIRED->value)>Истекли</option>
                    <option value="{{ SslStatus::ERROR->value }}" @selected(request('status') == SslStatus::ERROR->value)>Ошибка</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Сортировка:</span>
                <select class="border rounded-lg px-4 py-2" name="sort">
                    <option value="expired" @selected(request('sort') == 'valid_to')>Дата окончания</option>
                    <option value="domain_id" @selected(request('sort') == 'domain_id')>Домен</option>
                </select>
                <select class="border rounded-lg px-4 py-2" name="direction">
                    <option value="asc" @selected(request('direction') == 'asc')>По возрастанию</option>
                    <option value="desc" @selected(request('direction') == 'desc')>По убыванию</option>
                </select>
            </div>
        </div>

        <table class="w-full">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Домен</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата окончания</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            @foreach ($certificates as $cert)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $cert->domain->name }}</td>
                    <td class="px-6 py-4">
                        @switch($cert->status)
                            @case(SslStatus::OK->value)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Действителен</span>
                                @break
                            @case(SslStatus::EXPIRED->value)
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-sm">Истек</span>
                                @break
                            @case(SslStatus::ERROR->value)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Ошибка</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4">{{ $cert->expired?->translatedFormat('d F Y H:i') }}<br>
                        <span class="text-sm text-gray-500">({{ $cert->expired?->diffForHumans() }})</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="https://{{ $cert->domain->name }}"
                           target="_blank"
                           class="text-blue-500 hover:text-blue-600">
                            Перейти →
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-4 border-t">
            {{ $certificates->links() }}
        </div>
    </div>
    </form>
</div>
</body>
</html>