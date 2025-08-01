<?php

namespace App\Contracts\Repositories;

use App\Models\Enum\DomainStatus;

interface DomainRepositoryInterface {
    public function updateDomainStatus(string $domain, DomainStatus $status): void;
}