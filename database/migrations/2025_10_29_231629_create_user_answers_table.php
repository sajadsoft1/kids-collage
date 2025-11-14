<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->json('answer_data');

            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2);

            $table->boolean('is_correct')->nullable();
            $table->boolean('is_partially_correct')->nullable();

            $table->integer('time_spent')->nullable()->comment('seconds');

            $table->timestamp('answered_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->unique(['exam_attempt_id', 'question_id']);
            $table->index('exam_attempt_id');
            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
