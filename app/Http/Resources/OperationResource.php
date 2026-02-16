<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'offer_id' => $this->offer_id,
            'tenant_id' => $this->tenant_id,
            'buyer_id' => $this->buyer_id,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'shares_count' => $this->shares_count,
            'price_per_share' => $this->price_per_share,
            'amount_total' => $this->amount_total,
            'currency' => $this->currency ?? 'SAR',
            'payment_id' => $this->payment_id,
            'external_reference' => $this->external_reference,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get type label in Arabic
     */
    private function getTypeLabel(): string
    {
        return match($this->type) {
            'purchase' => 'شراء',
            'sale' => 'بيع',
            'transfer' => 'تحويل',
            default => $this->type,
        };
    }

    /**
     * Get status label in Arabic
     */
    private function getStatusLabel(): string
    {
        return match($this->status) {
            'completed' => 'مكتمل',
            'pending' => 'قيد الانتظار',
            'cancelled' => 'ملغي',
            'failed' => 'فاشل',
            default => $this->status,
        };
    }
}
