<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenants:activate-first', function () {
    $conn = 'central';
    $tenant = DB::connection($conn)->table('tenants')->orderBy('TenantID')->first();
    if (! $tenant) {
        $this->warn('No tenants found in central database.');
        return;
    }
    $now = Carbon::now()->toDateString();
    $end = Carbon::parse($now)->addYear()->subDay()->toDateString();
    DB::connection($conn)->table('tenants')->where('TenantID', $tenant->TenantID)->update([
        'IsActive' => 1,
        'Status' => 1,
        'SubscriptionStartDate' => $now,
        'SubscriptionEndDate' => $end,
        'TrialEndDate' => null,
        'UDate' => Carbon::now(),
    ]);
    $this->info('Activated tenant ID '.$tenant->TenantID.' (Subdomain='.$tenant->Subdomain.').');
})->purpose('Activate the first tenant in central DB');
