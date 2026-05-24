<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deceased_insurance_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Datos del fallecido
            $table->string('deceased_name');
            $table->string('deceased_dni', 20);
            $table->date('deceased_birth_date')->nullable();
            $table->date('deceased_death_date');
            $table->string('deceased_province', 80)->nullable();

            // Datos del solicitante (cliente de la asesoría)
            $table->string('applicant_name');
            $table->string('applicant_dni', 20);
            $table->string('applicant_relationship', 60);
            $table->string('applicant_email')->nullable();
            $table->string('applicant_phone', 30)->nullable();

            // Estado del trámite
            $table->enum('status', [
                'pendiente_documentacion',
                'tramite_iniciado',
                'certificado_recibido',
                'seguro_encontrado',
                'seguro_no_encontrado',
                'reclamacion_enviada',
                'cobrado',
            ])->default('pendiente_documentacion');

            // Resultado RCSCF
            $table->string('insurer_found')->nullable();
            $table->string('policy_type')->nullable();
            $table->decimal('insured_amount', 12, 2)->nullable();
            $table->string('rcscf_certificate_path')->nullable();

            // Notas internas
            $table->text('notes')->nullable();

            // Fechas clave
            $table->date('tramite_sent_at')->nullable();
            $table->date('certificate_received_at')->nullable();
            $table->date('claim_sent_at')->nullable();
            $table->date('resolved_at')->nullable();

            $table->timestamps();
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deceased_insurance_searches');
    }
};
