<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();

            // translations: title - description - body
            $table->text('languages')->nullable();
            $table->date('execution_date');

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();

            $table->boolean('published')->default(BooleanEnum::DISABLE->value);
            $table->timestamp('published_at')->nullable();

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('wish_count')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
