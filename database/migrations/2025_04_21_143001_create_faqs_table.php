<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            // translations: title, body
            $table->text('languages')->nullable();
            $table->foreignId('category_id')->index()->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('ordering')->default(1);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('favorite')->default(BooleanEnum::DISABLE->value);
            $table->boolean('published')->default(BooleanEnum::DISABLE->value);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
