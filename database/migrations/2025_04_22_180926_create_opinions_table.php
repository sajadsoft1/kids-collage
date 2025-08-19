<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('opinions', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->string('user_name');
            $table->string('company')->nullable();
            $table->text('comment');
            $table->unsignedInteger('ordering')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->softDeletes();
            $table->text('languages')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opinions');
    }
};
