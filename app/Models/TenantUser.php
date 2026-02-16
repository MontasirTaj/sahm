<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class TenantUser extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

    protected $connection = 'tenant';
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'must_change_password' => 'boolean',
    ];

    public function conversations()
    {
        return $this->belongsToMany(TenantConversation::class, 'conversation_participants', 'user_id', 'conversation_id')
            ->withTimestamps()
            ->withPivot(['joined_at', 'last_read_at']);
    }

    public function messages()
    {
        return $this->hasMany(TenantMessage::class, 'sender_id');
    }
}
