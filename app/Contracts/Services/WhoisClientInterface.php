<?php

namespace App\Contracts\Services;
use App\Dto\Domain\DomainCheckDTO;
use App\Models\Domain;

interface WhoisClientInterface {
    public function getDomainInfo(Domain $domain): DomainCheckDTO;
}