<?php

namespace App\Service\Ssl;

use App\Contracts\Services\SslClientInterface;
use App\Dto\Ssl\SslCheckDTO;
use App\Models\Enum\SslStatus;
use App\Models\SslCertificate;
use Illuminate\Support\Carbon;

class NativeSslClient implements SslClientInterface
{
    const TIMEOUT = 30;

    public function checkSsl(SslCertificate $sslCertificate): SslCheckDTO
    {
        $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $client = @stream_socket_client("ssl://{$sslCertificate->domain->name}:{$sslCertificate->port}", $errno, $errstr, self::TIMEOUT, STREAM_CLIENT_CONNECT, $context);
        $cert = null;

        if ($client) {
            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
        }

        return new SslCheckDTO(
            domain: $sslCertificate->domain->name,
            expirationDate: (isset($client) && is_array($cert)) ? $expirationDate = Carbon::parse($cert['validTo_time_t']) : null,
            status: (isset($client) && is_array($cert))
                ? $expirationDate->greaterThan(now()) ? SslStatus::OK : SslStatus::EXPIRED
                : SslStatus::ERROR,
            // @TODO: Передавать информацию о том, кто сделал сертификат
            issuer: (isset($client) && is_array($cert)) ? $cert['issuer']['O'] : null,
            errorMessage: $errstr,
        );
    }
}