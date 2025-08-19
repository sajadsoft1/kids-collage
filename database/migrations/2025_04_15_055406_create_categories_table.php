<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\CategoryTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // translation: title, description, body
            $table->text('languages')->nullable();
            $table->string('slug')->unique()->index();
            $table->foreignId('parent_id')->nullable()->constrained('categories');
            $table->string('type')->default(CategoryTypeEnum::BLOG->value);
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->unsignedInteger('ordering')->default(1);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->schemalessAttributes('extra_attributes');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
