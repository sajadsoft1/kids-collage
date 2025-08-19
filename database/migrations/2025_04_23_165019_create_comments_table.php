<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\YesNoEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('languages')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->morphs('morphable');
            $table->text('comment');
            $table->unsignedTinyInteger('rate')->nullable()->comment('1 to 5');
            $table->text('admin_note')->nullable();
            $table->boolean('suggest')->default(YesNoEnum::NO->value);
            $table->boolean('published')->default(BooleanEnum::DISABLE->value);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
