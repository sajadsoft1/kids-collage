<?php

declare(strict_types=1);

use App\Enums\PageTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // translations: title, body
            $table->string('type')->default(PageTypeEnum::RULES->value);
            $table->string('slug')->unique();
            $table->schemalessAttributes('extra_attributes');
            $table->unsignedBigInteger('view_count')->default(0);
            $table->text('languages')->nullable();
            $table->boolean('deletable')->default(\App\Enums\YesNoEnum::YES->value); // yes, no
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
