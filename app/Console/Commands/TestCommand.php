<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use App\Service\SslService;
use App\Service\WhoisService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Iodev\Whois\Factory;
use OpenSSLCertificate;
use Psy\Shell;
use Psy\Configuration;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск интерактивной консоли для тестирования';

    /**
     * Execute the console command.
     */
    public function handle()
    {

//
//
        $domain = Domain::find(2);
        app(WhoisService::class)->checkDomain($domain);
        (new SslService($domain))->checkSslForDomain();
//
//
        dd('done');
//        dd(Carbon::parse($info->expirationDate), $info->expirationDate);


        $domain = 'altair19.ru';
        $port = 443;
        $timeout = 10;

        $name = "{$domain}:{$port}";
        $error = "";
        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);

        // Открываем соединение
        $client = @stream_socket_client("ssl://{$domain}:{$port}", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
//        dd($client, $errno, $errstr);
        if (!$client) {
            $error =  [
                "name" => $name,
                "subject" => "",
                "issuer" => "",
                "algo" => "",
                "expires" => "",
                "sunset_date" => "",
                "error" => "Ошибка соединения: $errstr ($errno)"
            ];
        }

        $contextOptions = stream_context_get_params($client);
        /** @var OpenSSLCertificate $sslCertificate */
        $sslCertificate = $contextOptions["options"]["ssl"]["peer_certificate"];
        $cert = openssl_x509_parse($contextOptions["options"]["ssl"]["peer_certificate"]);
        $valid = Carbon::parse($cert["validTo_time_t"]);
        dd($cert, $valid);
//        fclose($client);

        dd($contextOptions, $error, $client);
        
        $this->info("Laravel Test Console");
        $this->line("Для выхода используйте exit или Ctrl+D");
        
        $shell->run();
    }


    function getCertInfo($domain, $port = 443, $timeout = 10)
    {
        $name = "{$domain}:{$port}";
        $error = "";
        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);

        // Открываем соединение
        $client = @stream_socket_client("ssl://{$domain}:{$port}", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);

        if (!$client) {
            return [
                "name" => $name,
                "subject" => "",
                "issuer" => "",
                "algo" => "",
                "expires" => "",
                "sunset_date" => "",
                "error" => "Ошибка соединения: $errstr ($errno)"
            ];
        }

        $contextOptions = stream_context_get_params($client);
        fclose($client);

        if (!isset($contextOptions["options"]["ssl"]["peer_certificate"])) {
            return [
                "name" => $name,
                "subject" => "",
                "issuer" => "",
                "algo" => "",
                "expires" => "",
                "sunset_date" => "",
                "error" => "Сертификат не найден"
            ];
        }

        $cert = openssl_x509_parse($contextOptions["options"]["ssl"]["peer_certificate"]);
        if (!$cert) {
            return [
                "name" => $name,
                "subject" => "",
                "issuer" => "",
                "algo" => "",
                "expires" => "",
                "sunset_date" => "",
                "error" => "Ошибка парсинга сертификата"
            ];
        }

        // Получаем Subject и Issuer
        $subject = $cert["subject"]["CN"] ?? "N/A";
        $issuer = $cert["issuer"]["CN"] ?? "N/A";

        // Определяем алгоритм подписи
        $algo = $cert["signatureTypeSN"] ?? "Unknown";

        // Определяем срок действия
        $now = time();
        $notAfter = $cert["validTo_time_t"];
        $timeLeft = $notAfter - $now;
        if ($timeLeft > 0) {
            $expires = floor($timeLeft / (60 * 60 * 24)) . " days";
        } else {
            $expires = floor($timeLeft / 3600) . " hours";
        }

        // Форматируем дату истечения
        $sunsetDate = date("Y-m-d H:i:s", $notAfter);

        // Проверяем соответствие домена сертификату
        $domains = $cert["extensions"]["subjectAltName"] ?? "";
        if ($domains && !str_contains($domains, $domain)) {
            $error = "Сертификат не соответствует домену";
        }

        return [
            "name" => $name,
            "subject" => $subject,
            "issuer" => $issuer,
            "algo" => $algo,
            "expires" => $expires,
            "sunset_date" => $sunsetDate,
            "error" => $error
        ];
    }

    function main()
    {
        // Список доменов
        $domains = [
            "abakan-master.ru", "agrosnab19.ru", "altair19.ru", "cbo19.ru",
            "ds-alenka.ru", "ds-skazka19.ru", "dverimp.ru", "dwtolk.ru",
            "hgs19.ru", "kalina19.ru", "polka19.ru", "sutyr19.ru",
            "trolleybus-abakan.ru", "wtolk.ru", "wtolk2.ru",
            "xn----8sbaabcy6aoca7ab1aviy.xn--p1ai", "xn----9sbmaomf0alflef3l.xn--p1ai",
            "xn---19-mdd0cgsdj4hra.xn--p1ai", "xn--19-6kc3berg.xn--p1ai",
            "xn--19-6kcafk4ehrl.xn--p1ai",
        ];

        // Вывод заголовка таблицы
        printf("%-40s %-30s %-35s %-20s %-15s %-25s %s\n",
            "NAME", "SUBJECT", "ISSUER", "ALGO", "EXPIRES", "SUNSET DATE", "ERROR");
        echo str_repeat("-", 150) . "\n";

        foreach ($domains as $domain) {
            $info = getCertInfo($domain);
            printf("%-40s %-30s %-35s %-20s %-15s %-25s %s\n",
                $info["name"], $info["subject"], $info["issuer"], $info["algo"],
                $info["expires"], $info["sunset_date"], $info["error"]);
        }
    }


}
