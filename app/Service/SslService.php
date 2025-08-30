<?php

namespace App\Service;

use App\Actions\Ssl\UpdateSslInfoFromDtoAction;
use App\Contracts\Services\SslClientInterface;
use App\Models\Domain;
use App\Models\Enum\SslStatus;
use App\Models\SslCertificate;

class SslService
{
    const DEFAULT_PORT = 443;
    protected Domain $domain;
    protected int $port = self::DEFAULT_PORT;
    public function __construct(
        protected SslClientInterface $sslClient,
        protected UpdateSslInfoFromDtoAction $updateSslInfoFromDtoAction,
    ) {}

    public function checkSslForDomain(Domain $domain, int $port = self::DEFAULT_PORT): void
    {
        $this->domain = $domain;
        $this->port = $port;
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
                'port' => $this->port,
                'status' => SslStatus::ERROR->value,
            ]);
            $certificates->push($certificate);
        }

        return $certificates;
    }

    public function checkSsl(SslCertificate $sslCertificate): void
    {
        $dto = $this->sslClient->checkSsl($sslCertificate);
        $this->updateSslInfoFromDtoAction->execute($sslCertificate, $dto);
    }
}