<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teammates', function (Blueprint $table) {
            $table->id();
            // title description bio
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->text('languages')->nullable();
            $table->string('position');
            $table->timestamp('birthday')->nullable();
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teammates');
    }
};
