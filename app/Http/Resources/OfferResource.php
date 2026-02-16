<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $soldPercentage = $this->total_shares > 0 
            ? round(($this->sold_shares / $this->total_shares) * 100, 2) 
            : 0;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'country' => $this->country,
            'city' => $this->city,
            'address' => $this->address,
            'total_shares' => $this->total_shares,
            'available_shares' => $this->available_shares,
            'sold_shares' => $this->sold_shares,
            'sold_percentage' => $soldPercentage,
            'price_per_share' => $this->price_per_share,
            'currency' => $this->currency ?? 'SAR',
            'status' => $this->status,
            'is_available' => $this->available_shares > 0,
            'is_active' => $this->status === 'active',
            'starts_at' => $this->starts_at ? $this->starts_at->format('Y-m-d H:i:s') : null,
            'ends_at' => $this->ends_at ? $this->ends_at->format('Y-m-d H:i:s') : null,
            'cover_image' => $this->cover_image,
            'media' => $this->media,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
