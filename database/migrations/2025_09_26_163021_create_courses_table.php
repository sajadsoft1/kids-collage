<?php

declare(strict_types=1);

use App\Enums\CourseTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->index()->constrained()->cascadeOnDelete();
            $table->unsignedDecimal('price', 10, 2)->default(0);
            $table->string('type')->index()->default(CourseTypeEnum::INPERSON->value); // @see CourseTypeEnum
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->text('languages')->nullable();
            $table->timestamps();

            // Composite index for date range queries
            $table->index(['start_date', 'end_date']);

            // Data integrity constraints
            $table->check('start_date < end_date'); // Course start date must be before end date
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
