<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeceasedInsuranceSearch extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'deceased_name', 'deceased_dni', 'deceased_birth_date', 'deceased_death_date', 'deceased_province',
        'applicant_name', 'applicant_dni', 'applicant_relationship', 'applicant_email', 'applicant_phone',
        'status',
        'insurer_found', 'policy_type', 'insured_amount', 'rcscf_certificate_path',
        'notes',
        'tramite_sent_at', 'certificate_received_at', 'claim_sent_at', 'resolved_at',
    ];

    protected $casts = [
        'deceased_birth_date'    => 'date',
        'deceased_death_date'    => 'date',
        'tramite_sent_at'        => 'date',
        'certificate_received_at'=> 'date',
        'claim_sent_at'          => 'date',
        'resolved_at'            => 'date',
        'insured_amount'         => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pendiente_documentacion' => 'Pendiente documentación',
            'tramite_iniciado'        => 'Trámite iniciado',
            'certificado_recibido'    => 'Certificado recibido',
            'seguro_encontrado'       => 'Seguro encontrado',
            'seguro_no_encontrado'    => 'Sin seguro registrado',
            'reclamacion_enviada'     => 'Reclamación enviada',
            'cobrado'                 => 'Cobrado',
            default                  => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pendiente_documentacion' => 'secondary',
            'tramite_iniciado'        => 'primary',
            'certificado_recibido'    => 'info',
            'seguro_encontrado'       => 'warning',
            'seguro_no_encontrado'    => 'danger',
            'reclamacion_enviada'     => 'warning',
            'cobrado'                 => 'success',
            default                  => 'secondary',
        };
    }

    public function nextStep(): string
    {
        return match($this->status) {
            'pendiente_documentacion' => 'Reúne el certificado de defunción y DNI del solicitante, luego inicia el trámite en el Ministerio de Justicia.',
            'tramite_iniciado'        => 'El Ministerio tarda entre 10 y 15 días hábiles en emitir el certificado RCSCF.',
            'certificado_recibido'    => 'Revisa el certificado. Si hay seguro registrado, actualiza el estado y genera la carta de reclamación.',
            'seguro_encontrado'       => 'Genera la carta de reclamación a la aseguradora y envíala junto con el certificado de defunción.',
            'seguro_no_encontrado'    => 'Sin seguros de fallecimiento registrados a nombre del fallecido. Cierra el expediente.',
            'reclamacion_enviada'     => 'La aseguradora tiene 1 mes para responder (art. 20 LCS). Si no responde, escalar a DGSFP.',
            'cobrado'                 => 'Expediente cerrado. El beneficiario ha cobrado el capital asegurado.',
            default                  => '',
        };
    }
}
