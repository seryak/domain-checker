<?php

namespace App\Dto\Domain;

use App\Models\Enum\DomainStatus;
use Illuminate\Support\Carbon;

readonly class DomainCheckDTO
{
    public function __construct(
        public string  $domain,
        public ?Carbon $expirationDate = null,
        public DomainStatus $status,
        public ?string $errorMessage = null,
    ) {}
}