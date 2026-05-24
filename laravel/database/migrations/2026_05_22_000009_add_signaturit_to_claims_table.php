<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('signaturit_id', 100)->nullable()->after('viability_analysis');
            $table->timestamp('signed_at')->nullable()->after('signaturit_id');
            $table->index('signaturit_id');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex(['signaturit_id']);
            $table->dropColumn(['signaturit_id', 'signed_at']);
        });
    }
};
