<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DevTask extends Model
{
    protected $fillable = [
        'task_ref',
        'description',
        'status',
        'commit_sha',
        'error_log',
        'n8n_run_id',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function n8nRuns(): HasMany
    {
        return $this->hasMany(N8nRun::class, 'dev_task_id');
    }
}
