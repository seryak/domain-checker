<?php

namespace App\Actions\Domain;

use App\Dto\Domain\DomainCheckDTO;
use App\Models\Domain;
use App\Models\Enum\DomainStatus;

class UpdateDomainInfoFromDtoAction
{
    public function execute(Domain $domain, DomainCheckDTO $dto): void
    {
        if ($dto->status === DomainStatus::ERROR) {
            $domain->update(['status' => DomainStatus::ERROR->value]);
            $domain->errorMessages()->create([
                'text' => $dto->errorMessage,
            ]);
        } else {
            if ($dto->expirationDate?->lessThanOrEqualTo(now())) {
                $domain->update(['status' => DomainStatus::EXPIRED->value]);
            } else {
                $domain->update(['status' => DomainStatus::OK->value, 'expired_at' => $dto->expirationDate->startOfDay()]);
            }
        }
    }
}