<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('claim_type', ['insurance'])->default('insurance');
            $table->string('insurer_name');
            $table->text('description');
            $table->string('claimant_name');
            $table->string('claimant_dni');
            $table->string('claimant_email');
            $table->string('claimant_phone')->nullable();
            $table->text('claimant_address');
            $table->string('policy_number')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('claimant_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
