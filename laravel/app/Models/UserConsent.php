<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserConsent extends Model
{
    public $timestamps = false;
    protected $table = 'user_consents';

    protected $fillable = [
        'user_id', 'session_id', 'consent_type', 'granted',
        'version', 'ip_address', 'user_agent', 'metadata', 'created_at',
    ];

    protected $casts = [
        'granted'    => 'boolean',
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];
}
