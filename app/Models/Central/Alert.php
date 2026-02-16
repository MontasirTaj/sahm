<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $connection = 'central';
    protected $table = 'alerts';
    protected $fillable = [
        'scope','tenant_id','type','title','message','data','is_read'
    ];
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];
}
