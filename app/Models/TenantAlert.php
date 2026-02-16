<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantAlert extends Model
{
    protected $connection = 'tenant';
    protected $table = 'alerts';
    protected $fillable = [
        'type','title','message','data','is_read'
    ];
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];
}
