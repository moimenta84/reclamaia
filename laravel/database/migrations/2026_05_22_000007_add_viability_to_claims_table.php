<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->enum('viability_score', ['alta', 'media', 'baja'])->nullable()->after('status');
            $table->integer('viability_probability')->nullable()->after('viability_score');
            $table->json('viability_analysis')->nullable()->after('viability_probability');
            $table->string('policy_pdf_path', 500)->nullable()->after('policy_number');
            $table->json('policy_clauses')->nullable()->after('policy_pdf_path');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['viability_score', 'viability_probability', 'viability_analysis', 'policy_pdf_path', 'policy_clauses']);
        });
    }
};
