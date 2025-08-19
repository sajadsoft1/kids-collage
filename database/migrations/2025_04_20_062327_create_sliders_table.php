<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\SliderPositionEnum;
use App\Enums\YesNoEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            // translations: title, description
            $table->text('languages')->nullable();
            $table->string('position')->default(SliderPositionEnum::TOP);  // new
            $table->string('link')->nullable();  // new
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->boolean('has_timer')->default(YesNoEnum::NO->value);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('timer_start')->nullable();
            $table->unsignedInteger('ordering')->default(1);
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
