<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class AdminNotification extends Model
{
    protected $connection = 'central';
    protected $table = 'admin_notifications';
    
    protected $fillable = [
        'type',
        'title',
        'message',
        'offer_id',
        'tenant_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * العلاقة مع العرض
     */
    public function offer()
    {
        return $this->belongsTo(ShareOffer::class, 'offer_id');
    }

    /**
     * العلاقة مع التينانت
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope للتنبيهات غير المقروءة
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get icon based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'new_offer' => 'mdi-file-document-plus',
            'offer_resubmitted' => 'mdi-file-refresh',
            default => 'mdi-bell'
        };
    }

    /**
     * Get color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'new_offer' => 'primary',
            'offer_resubmitted' => 'warning',
            default => 'info'
        };
    }
}
