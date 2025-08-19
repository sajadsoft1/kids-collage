<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\SocialMediaPositionEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->text('languages')->nullable();
            $table->string('link');
            $table->unsignedInteger('ordering')->default(1);
            $table->boolean('published')->default(BooleanEnum::DISABLE->value);
            $table->string('position')->default(SocialMediaPositionEnum::ALL->value);
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media');
    }
};
