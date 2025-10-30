<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->decimal('weight', 8, 2);
            $table->integer('order');

            $table->boolean('is_required')->default(true);
            $table->json('config_override')->nullable();

            $table->timestamps();

            $table->unique(['exam_id', 'question_id']);
            $table->index('exam_id');
            $table->index('question_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_question');
    }
};
