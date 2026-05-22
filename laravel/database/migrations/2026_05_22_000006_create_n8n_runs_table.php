<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('n8n_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dev_task_id')->nullable()->constrained('dev_tasks')->nullOnDelete();
            $table->enum('result', ['success', 'failure', 'skipped']);
            $table->text('error_message')->nullable();
            $table->json('files_modified')->nullable();
            $table->boolean('telegram_notified')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('n8n_runs');
    }
};
