<?php

namespace App\Services;

use App\Models\Central\ShareOffer as CentralShareOffer;
use App\Models\TenantShareOffer;
use App\Models\Tenant;
use App\Services\AlertService;

class ShareOfferSyncService
{
    public function upsertToCentral(TenantShareOffer $tenantOffer, Tenant $tenant): CentralShareOffer
    {
        $payload = [
            'tenant_id' => $tenant->TenantID,
            'title' => $tenantOffer->title,
            'title_ar' => $tenantOffer->title_ar,
            'description' => $tenantOffer->description,
            'description_ar' => $tenantOffer->description_ar,
            'city' => $tenantOffer->city,
            'address' => $tenantOffer->address,
            'total_shares' => $tenantOffer->total_shares,
            'available_shares' => $tenantOffer->available_shares,
            'sold_shares' => $tenantOffer->sold_shares,
            'price_per_share' => $tenantOffer->price_per_share,
            'currency' => $tenantOffer->currency,
            'status' => $tenantOffer->status,
            'starts_at' => $tenantOffer->starts_at,
            'ends_at' => $tenantOffer->ends_at,
            'cover_image' => $tenantOffer->cover_image,
            'media' => $tenantOffer->media,
            'metadata' => $tenantOffer->metadata,
        ];

        if ($tenantOffer->central_offer_id) {
            $central = CentralShareOffer::on('central')->find($tenantOffer->central_offer_id);
            if ($central) {
                $central->fill($payload)->save();
                return $central;
            }
        }

        $central = CentralShareOffer::on('central')->create($payload);
        $tenantOffer->central_offer_id = $central->id;
        $tenantOffer->save();

        // Notify central admins about new offer
        app(AlertService::class)->adminOfferCreated($tenant->TenantID, [
            'title' => $tenantOffer->title,
            'offer_id' => $central->id,
            'tenant_id' => $tenant->TenantID,
            'subdomain' => $tenant->Subdomain,
        ]);

        // Notify tenant (local) about new offer for their bell
        app(AlertService::class)->tenantNotify(
            $tenant->TenantID,
            config('database.connections.tenant'),
            'offer_created',
            __('تم إنشاء عرض أسهم جديد'),
            $tenantOffer->title,
            [
                'offer_id' => $tenantOffer->id,
                'central_offer_id' => $central->id,
                'subdomain' => $tenant->Subdomain,
            ]
        );

        return $central;
    }
}
