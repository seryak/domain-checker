<?php

namespace App\Dto\Ssl;

use App\Models\Enum\DomainStatus;
use App\Models\Enum\SslStatus;
use Illuminate\Support\Carbon;

readonly class SslCheckDTO
{
    public function __construct(
        public string    $domain,
        public ?Carbon   $expirationDate = null,
        public SslStatus $status,
        public ?string   $issuer,
        public ?string   $errorMessage = null,
    ) {}
}