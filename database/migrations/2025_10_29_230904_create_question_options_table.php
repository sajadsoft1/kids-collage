<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();

            $table->text('content');
            // Store enum as string (text, image, html)
            $table->string('type', 20)->default('text');

            $table->boolean('is_correct')->default(false);
            $table->integer('order')->default(0);

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('question_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
