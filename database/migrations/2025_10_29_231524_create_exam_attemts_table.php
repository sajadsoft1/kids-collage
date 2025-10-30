<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();

            $table->decimal('total_score', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();

            // Store enum as string (AttemptStatusEnum values)
            $table->string('status', 20)->default('in_progress');

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('exam_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
