<?php

declare(strict_types=1);

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->index()->constrained('courses')->cascadeOnDelete();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->string('status')->index()->default(EnrollmentStatus::PENDING->value);
            $table->timestamp('enrolled_at');
            $table->decimal('progress_percent', 5, 2)->default(0.00);
            $table->timestamps();

            // Unique constraint to prevent duplicate enrollments
            $table->unique(['user_id', 'course_id']);

            // Indexes for performance
            $table->index('enrolled_at');
            $table->index('progress_percent');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
