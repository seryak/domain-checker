<?php

namespace App\Service;

use App\Actions\Domain\UpdateDomainInfoFromDtoAction;
use App\Contracts\Services\WhoisClientInterface;
use App\Models\Domain;

readonly class WhoisService
{
    public function __construct(
        private WhoisClientInterface $whoisClient,
        private UpdateDomainInfoFromDtoAction $updateDomainInfoFromDtoAction,
//        private DomainRepositoryInterface     $repository
    ) {}

    /**
     * Check domain expiration date and update status
     *
     * @param Domain $domain
     * @return void
     */
    public function checkDomain(Domain $domain): void
    {
        $info = $this->whoisClient->getDomainInfo($domain);
        $this->updateDomainInfoFromDtoAction->execute($domain, $info);
    }
}