<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leitner_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('flash_card_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('box')->default(1);
            $table->boolean('finished')->default(false);
            $table->dateTime('next_review_at');
            $table->dateTime('last_review_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leitner_boxes');
    }
};
