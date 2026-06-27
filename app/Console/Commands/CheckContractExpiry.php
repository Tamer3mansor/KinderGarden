<?php

namespace App\Console\Commands;

use App\Jobs\CheckContractExpiryJob;
use Illuminate\Console\Command;

class CheckContractExpiry extends Command
{
    protected $signature   = 'contracts:check-expiry';
    protected $description = 'Check teachers with contracts expiring within 2 months';

    public function handle(): void
    {
        CheckContractExpiryJob::dispatch();
        $this->info('Contract expiry check dispatched to the queue.');
    }
}