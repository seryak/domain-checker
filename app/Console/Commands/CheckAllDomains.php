<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Service\SslService;
use App\Service\WhoisService;
use Illuminate\Console\Command;

class CheckAllDomains extends Command
{
    protected $signature = 'domains:check-all';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $whoisService = app(WhoisService::class);
            $whoisService->checkDomain($domain);
            $sslService = app(SslService::class);
            $sslService->checkSslForDomain($domain);
        }
        
        $this->info("Domains checked successfully.");
    }
}
