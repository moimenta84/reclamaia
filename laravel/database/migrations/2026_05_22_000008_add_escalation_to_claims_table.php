<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->timestamp('sent_to_insurer_at')->nullable()->after('viability_analysis');
            $table->enum('escalation_status', ['none', 'reminder_sent', 'dgsfp_escalated', 'resolved'])->default('none')->after('sent_to_insurer_at');
            $table->timestamp('escalation_sent_at')->nullable()->after('escalation_status');
            $table->string('escalation_document_path', 500)->nullable()->after('escalation_sent_at');

            $table->index(['status', 'sent_to_insurer_at']);
        });

        Schema::create('escalation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['reminder_30d', 'dgsfp_escalation', 'resolved'])->default('reminder_30d');
            $table->string('document_path', 500)->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['sent_to_insurer_at', 'escalation_status', 'escalation_sent_at', 'escalation_document_path']);
        });
        Schema::dropIfExists('escalation_logs');
    }
};
