<?php

namespace App\Service\Whois;

use App\Contracts\Services\WhoisClientInterface;
use App\Dto\Domain\DomainCheckDTO;
use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use Illuminate\Support\Carbon;
use Iodev\Whois\Factory;

class IodevWhoisClient implements WhoisClientInterface
{
    public function getDomainInfo(Domain $domain): DomainCheckDTO
    {
        $whois = Factory::get()->createWhois();
        $domainName = trim($domain->name);
        if (empty($domainName)) {
            throw new \InvalidArgumentException(__('Domain is empty'));
        }
        try {
            $info = $whois->loadDomainInfo($domainName);
            if ($info === null) {
                return new DomainCheckDTO($domainName, null, DomainStatus::ERROR, __('Something went wrong'));
            }

            $expirationDate = Carbon::parse($info->expirationDate)->startOfDay();
            $status = $expirationDate->lessThanOrEqualTo(now()) ? DomainStatus::EXPIRED : DomainStatus::OK;

            return new DomainCheckDTO(
                domain: $domainName,
                expirationDate: $expirationDate,
                status: $status
            );
        } catch (\Exception $e) {
            return new DomainCheckDTO($domainName, null, DomainStatus::ERROR, $e->getMessage());
        }
    }
}