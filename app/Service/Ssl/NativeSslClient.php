<?php

namespace App\Service\Ssl;

use App\Contracts\Services\SslClientInterface;
use App\Dto\Ssl\SslCheckDTO;
use App\Models\Enum\SslStatus;
use App\Models\SslCertificate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class NativeSslClient implements SslClientInterface
{
    const TIMEOUT = 60;

    public function checkSsl(SslCertificate $sslCertificate): SslCheckDTO
    {
        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "SNI_enabled" => true,
                "peer_name" => $sslCertificate->domain->name,
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ]);

        try {
            $client = stream_socket_client("ssl://{$sslCertificate->domain->name}:{$sslCertificate->port}", $errno, $errstr, self::TIMEOUT, STREAM_CLIENT_CONNECT, $context);
        } catch (\Exception $e) {
            Log::info("SSL Check: Starting for domain {$sslCertificate->domain->name} on port {$sslCertificate->port}");
            Log::error("SSL Check: Exception during socket creation: " . $e->getMessage());
            $client = false;
            $errno = $e->getCode();
            $errstr = $e->getMessage();
        }
        $cert = null;

        if (!$client && $errstr) {
            Log::error("SSL Check: Socket creation failed: errno {$errno}, errstr: {$errstr}");
        } elseif (!$client) {
            Log::error("SSL Check: Socket creation failed silently, errno {$errno}");
        }

        if ($client) {
            $params = stream_context_get_params($client);
            $cert = openssl_x509_parse($params["options"]["ssl"]["peer_certificate"]);
            try {
                fclose($client);
            } catch (\Exception $e) {
                Log::warning("SSL Check: Failed to close socket: " . $e->getMessage());
            }
        }

        return new SslCheckDTO(
            domain: $sslCertificate->domain->name,
            expirationDate: (isset($client) && is_array($cert)) ? $expirationDate = Carbon::parse($cert['validTo_time_t']) : null,
            status: (isset($client) && is_array($cert))
                ? ($expirationDate->greaterThan(now()) ? SslStatus::OK : SslStatus::EXPIRED)
                : SslStatus::ERROR,
            // @TODO: Передавать информацию о том, кто сделал сертификат
            issuer: (isset($client) && is_array($cert)) ? $cert['issuer']['O'] : null,
            errorMessage: $errstr,
        );
    }
}