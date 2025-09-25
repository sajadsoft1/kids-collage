<?php

declare(strict_types=1);

use App\Enums\BannerSizeEnum;
use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('size')->default(BannerSizeEnum::S1X1->value);
            $table->string('link')->nullable();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->unsignedBigInteger('click')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->text('languages')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
