<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dev_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_ref', 20)->unique();
            $table->text('description');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('commit_sha', 40)->nullable();
            $table->text('error_log')->nullable();
            $table->string('n8n_run_id', 100)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('task_ref');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dev_tasks');
    }
};
