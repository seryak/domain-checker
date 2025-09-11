<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Native\Laravel\Facades\Settings;
use Composer\Semver\Comparator;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CheckAppUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app-updates:check {--force : Force update check even if recently checked}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет наличие обновлений приложения через API sslpatrol.io';

    /**
     * API endpoint для проверки обновлений.
     *
     * @var string
     */
    private const API_URL = 'https://sslpatrol.io/api/version/latest';

    /**
     * Ключ настройки для хранения последней версии.
     *
     * @var string
     */
    private const LATEST_VERSION_KEY = 'app_latest_version';

    /**
     * Ключ настройки для хранения времени последней проверки.
     *
     * @var string
     */
    private const LAST_CHECK_KEY = 'app_updates_last_check';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Запуск проверки обновлений приложения...');

        $startTime = microtime(true);

        try {
            // Выполняем HTTP запрос к API
            $response = $this->fetchVersionData();

            if (!$response) {
                $this->error('❌ Не удалось получить данные о версии');
                return CommandAlias::FAILURE;
            }

            // Обрабатываем полученные данные
            $this->processVersionData($response);

            // Сохраняем время проверки
            Settings::set(self::LAST_CHECK_KEY, now()->toISOString());

            $endTime = microtime(true);
            $took = round($endTime - $startTime, 2);

            $this->info("✅ Проверка завершена успешно ({$took} сек)");
            $this->displayVersionInfo();

            return CommandAlias::SUCCESS;

        } catch (\Exception $e) {
            $errorMessage = "❌ Ошибка проверки обновлений: {$e->getMessage()}";
            Log::error($errorMessage);
            $this->error($errorMessage);

            return CommandAlias::FAILURE;
        }
    }

    /**
     * Выполняет запрос к API для получения данных о версии.
     *
     * @return array|null
     */
    private function fetchVersionData(): ?array
    {
        $response = Http::timeout(30)->get(self::API_URL);

        if (!$response->successful()) {
            Log::warning("HTTP error: {$response->status()}");
            return null;
        }

        $data = $response->json();

        if (!isset($data['latest'])) {
            Log::warning('Latest version not found in API response');
            return null;
        }

        return $data;
    }

    /**
     * Обрабатывает полученные данные о версии.
     *
     * @param array $data
     */
    private function processVersionData(array $data): void
    {
        $currentVersion = config('nativephp.version');
        $latestVersion = $data['latest'];

        // Сохраняем последнюю версию
        Settings::set(self::LATEST_VERSION_KEY, $latestVersion);

        $comparison = version_compare($latestVersion, $currentVersion);

        if ($comparison > 0) {
            $message = "🔥 Доступна новая версия: {$latestVersion} (текущая: {$currentVersion})";
            $this->warn($message);
            Log::info("New version available: {$latestVersion}");
        } elseif ($comparison === 0) {
            $this->info("ℹ️ Версия актуальна: {$currentVersion}");
        } else {
            $message = "ℹ️ Текущая версия новее последней: {$currentVersion} > {$latestVersion}";
            $this->warn($message);
            Log::warning("Current version is newer than latest: {$currentVersion}");
        }
    }

    /**
     * Отображает информацию о версиях.
     */
    private function displayVersionInfo(): void
    {
        $currentVersion = config('nativephp.version');
        $latestVersion = Settings::get(self::LATEST_VERSION_KEY);
        $lastCheck = Settings::get(self::LAST_CHECK_KEY);

        $this->line('');
        $this->line('📋 Информация о версиях:');
        $this->line("   Текущая версия: {$currentVersion}");
        $this->line("   Последняя версия: {$latestVersion}");

        if ($lastCheck) {
            $lastCheckFormatted = \Carbon\Carbon::parse($lastCheck)->format('d.m.Y H:i:s');
            $this->line("   Последняя проверка: {$lastCheckFormatted}");
        }
    }
}