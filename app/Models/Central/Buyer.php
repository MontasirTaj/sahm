<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $connection = 'central';
    protected $table = 'buyers';
    protected $fillable = [
        'user_id','full_name','email','phone','national_id','date_of_birth','country','city','address','kyc_status','metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'date_of_birth' => 'date',
    ];
}
