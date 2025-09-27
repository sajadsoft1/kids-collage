<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\CourseTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->boolean('published')->index()->default(BooleanEnum::ENABLE->value);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->index()->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('type')->index()->default(CourseTypeEnum::INPERSON->value); // @see CourseTypeEnum
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('wish_count')->default(0);
            $table->text('languages')->nullable();
            $table->timestamps();

            // Composite index for date range queries
            $table->index(['start_date', 'end_date']);

            // Data integrity constraints
            // Note: Check constraints are not supported in Laravel migrations by default
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
