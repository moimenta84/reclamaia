<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'claim_id',
        'word_path',
        'pdf_path',
        'download_count',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'download_count' => 'integer',
    ];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
