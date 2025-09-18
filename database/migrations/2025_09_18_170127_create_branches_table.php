<?php

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            // translation: title, description
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->schemalessAttributes('extra_attributes');
            $table->text('languages')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
