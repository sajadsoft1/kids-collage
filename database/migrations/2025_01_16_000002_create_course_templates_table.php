<?php

declare(strict_types=1);

use App\Enums\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_templates', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('level')->nullable();
            $table->json('prerequisites')->nullable();
            $table->boolean('is_self_paced')->default(false);
            $table->string('type')->default(CourseType::IN_PERSON->value);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('wish_count')->default(0);
            $table->json('languages')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('category_id');
            $table->index('level');
            $table->index('type');
            $table->index('is_self_paced');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_templates');
    }
};
