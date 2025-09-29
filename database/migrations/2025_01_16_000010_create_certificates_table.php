<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->date('issue_date');
            $table->string('grade', 1);
            $table->string('certificate_path');
            $table->string('signature_hash');
            $table->timestamps();

            // Indexes for performance
            $table->index('enrollment_id');
            $table->index('issue_date');
            $table->index('grade');
            $table->index('signature_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
