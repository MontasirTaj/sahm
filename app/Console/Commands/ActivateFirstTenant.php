<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivateFirstTenant extends Command
{
    protected $signature = 'tenants:activate-first';
    protected $description = 'Activate the first tenant (ID=lowest) with default subscription dates';

    public function handle(): int
    {
        $conn = 'central';
        $tenant = DB::connection($conn)->table('tenants')->orderBy('TenantID')->first();
        if (! $tenant) {
            $this->warn('No tenants found in central database.');
            return self::SUCCESS;
        }

        $now = Carbon::now()->toDateString();
        $end = Carbon::parse($now)->addYear()->subDay()->toDateString();

        DB::connection($conn)->table('tenants')
            ->where('TenantID', $tenant->TenantID)
            ->update([
                'IsActive' => 1,
                'Status' => 1,
                'SubscriptionStartDate' => $now,
                'SubscriptionEndDate' => $end,
                'TrialEndDate' => null,
                'UDate' => Carbon::now(),
            ]);

        $this->info('Activated tenant ID '.$tenant->TenantID.' (Subdomain='.$tenant->Subdomain.').');
        return self::SUCCESS;
    }
}
