<?php

namespace App\Service;

use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use App\Models\Enum\SslStatus;
use App\Models\SslCertificate;
use Illuminate\Support\Carbon;

class SslService
{
    const DEFAULT_PORT = 443;
    public function __construct(protected Domain $domain)
    {
    }

    public function checkSslForDomain(): void
    {
        $certificates = $this->getCertificates();
        foreach ($certificates as $certificate) {
            $this->checkSsl($certificate);
        }
    }

    protected function getCertificates()
    {
        $certificates = $this->domain->sslCertificates;
        if ($certificates->isEmpty()) {
            $certificate = $this->domain->sslCertificates()->create([
                'port' => self::DEFAULT_PORT,
                'status' => SslStatus::ERROR->value,
            ]);
            $certificates->push($certificate);
        }

        return $certificates;
    }

    public function checkSsl(SslCertificate $sslCertificate, int $port = 443, int $timeout = 30): void
    {
        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $client = @stream_socket_client("ssl://{$sslCertificate->domain->name}:{$port}", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        if (!$client) {
            $sslCertificate->update(['status' => SslStatus::ERROR->value]);
        } else {
            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
            $sslCertificate->update([
                'status' => DomainStatus::OK->value,
                'expired' => Carbon::parse($cert['validTo_time_t']),
            ]);
        }
    }
}