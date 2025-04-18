<?php

namespace App\Service;

use App\Models\Domain;
use App\Models\Enum\DomainStatus;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Factory;
use Iodev\Whois\Modules\Tld\TldInfo;
use Iodev\Whois\Whois;

class WhoisService
{
    protected Whois $whois;
    public function __construct()
    {
        $this->whois = Factory::get()->createWhois();
    }

    /**
     * get whois information for domain.
     *
     * @param Domain $domain domain (example.com)
     * @return TldInfo|null result WHOIS query
     */
    public function getDomainInfo(Domain $domain): ?TldInfo
    {
        $domain = trim($domain->name);
        if (empty($domain)) {
            throw new InvalidArgumentException(__('Domain is empty'));
        }

        try {
            return $this->whois->loadDomainInfo($domain);
        } catch (ConnectionException|WhoisException|ServerMismatchException $e) {
            $domain->update(['status' => DomainStatus::ERROR->value]);
            return null;
        }
    }

    /**
     * Check domain expiration date and update status
     *
     * @param Domain $domain
     * @return void
     */
    public function checkDomain(Domain $domain): void
    {
        $info = $this->getDomainInfo($domain);
        if ($info === null) {
            return;
        } else {
            $carbon = Carbon::parse($info->expirationDate);
            if ($carbon->lessThanOrEqualTo(now())) {
                $domain->update(['status' => DomainStatus::EXPIRED->value]);
            } else {
                $domain->update(['status' => DomainStatus::OK->value, 'expired_at' => $carbon->startOfDay()]);
            }
        }
    }
}