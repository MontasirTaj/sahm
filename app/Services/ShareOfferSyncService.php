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
            'country' => $tenantOffer->country,
            'city' => $tenantOffer->city,
            'address' => $tenantOffer->address,
            'property_type' => $tenantOffer->property_type ?? null,
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
                // التحقق من حالة الرفض - إذا كان العرض مرفوضاً، أعد تعيينه للمراجعة
                $wasRejected = in_array($central->approval_status, ['rejected', 'real_estate_rejected']);
                
                $central->fill($payload)->save();
                
                // إذا كان العرض مرفوضاً وتم تعديله، أعده للمراجعة الأولية
                if ($wasRejected) {
                    $central->update([
                        'approval_status' => 'pending_approval',
                        'approval_progress' => 0,
                        'submitted_at' => now(),
                        'rejection_notes' => null,
                        'first_reviewed_at' => null,
                        'real_estate_reviewed_at' => null,
                        'reviewed_by' => null,
                    ]);
                    
                    // إنشاء إشعار للأدمن عن إعادة تقديم العرض
                    \App\Models\Central\AdminNotification::create([
                        'type' => 'offer_resubmitted',
                        'title' => 'إعادة تقديم عرض بعد التعديل',
                        'message' => sprintf('تم إعادة تقديم العرض #%d من %s (%s) بعد إجراء التعديلات',
                            $central->id,
                            $tenant->TenantName,
                            $tenant->Subdomain
                        ),
                        'offer_id' => $central->id,
                        'tenant_id' => $tenant->TenantID,
                    ]);
                }
                
                return $central;
            }
        }

        // للعروض الجديدة: تعيين حالة الموافقة الأولية
        $payload['approval_status'] = 'pending_approval';
        $payload['approval_progress'] = 0;
        $payload['submitted_at'] = now();

        $central = CentralShareOffer::on('central')->create($payload);
        $tenantOffer->central_offer_id = $central->id;
        $tenantOffer->save();

        // إنشاء تنبيه للأدمن عن العرض الجديد
        \App\Models\Central\AdminNotification::create([
            'type' => 'new_offer',
            'title' => 'عرض جديد بانتظار المراجعة',
            'message' => sprintf('تم تقديم عرض جديد من %s (%s) بانتظار المراجعة الأولية', 
                $tenant->Name, 
                $tenant->Subdomain
            ),
            'offer_id' => $central->id,
            'tenant_id' => $tenant->TenantID,
        ]);

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
