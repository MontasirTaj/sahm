<?php

namespace App\Services;

use App\Models\Central\Alert as CentralAlert;
use Illuminate\Support\Facades\DB;

class AlertService
{
    public function adminOfferCreated(int $tenantId, array $payload): void
    {
        CentralAlert::on('central')->create([
            'scope' => 'admin',
            'tenant_id' => $tenantId,
            'type' => 'offer_created',
            'title' => __('عرض أسهم جديد'),
            'message' => $payload['title'] ?? null,
            'data' => $payload,
        ]);
    }

    public function adminPurchaseCompleted(int $tenantId, array $payload): void
    {
        CentralAlert::on('central')->create([
            'scope' => 'admin',
            'tenant_id' => $tenantId,
            'type' => 'purchase_completed',
            'title' => __('عملية شراء أسهم مكتملة'),
            'message' => $payload['message'] ?? null,
            'data' => $payload,
        ]);
    }

    public function tenantNotify(int $tenantId, array $conn, string $type, string $title, ?string $message, array $data = []): void
    {
        config(['database.connections.tenant' => $conn]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::connection('tenant')->table('alerts')->insert([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
