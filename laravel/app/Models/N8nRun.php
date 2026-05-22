<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class N8nRun extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'dev_task_id',
        'result',
        'error_message',
        'files_modified',
        'telegram_notified',
        'started_at',
        'finished_at',
        'created_at',
    ];

    protected $casts = [
        'files_modified' => 'array',
        'telegram_notified' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function devTask(): BelongsTo
    {
        return $this->belongsTo(DevTask::class);
    }
}
