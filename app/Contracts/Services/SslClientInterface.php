<?php

namespace App\Contracts\Services;
use App\Dto\Ssl\SslCheckDTO;
use App\Models\SslCertificate;

interface SslClientInterface {
    public function checkSsl(SslCertificate $sslCertificate): SslCheckDTO;
}