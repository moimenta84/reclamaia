<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Claim extends Model
{
    use HasFactory;
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'claim_type',
        'insurer_name',
        'description',
        'claimant_name',
        'claimant_dni',
        'claimant_email',
        'claimant_phone',
        'claimant_address',
        'policy_number',
        'policy_pdf_path',
        'policy_clauses',
        'status',
        'viability_score',
        'viability_probability',
        'viability_analysis',
        'signaturit_id',
        'signed_at',
        'sent_to_insurer_at',
        'escalation_status',
        'escalation_sent_at',
        'escalation_document_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'viability_analysis' => 'array',
        'policy_clauses' => 'array',
        'viability_probability' => 'integer',
        'signed_at'           => 'datetime',
        'sent_to_insurer_at'  => 'datetime',
        'escalation_sent_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isPaid(): bool
    {
        return $this->payment?->status === Payment::STATUS_COMPLETED ?? false;
    }
}
