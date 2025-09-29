<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('level')->nullable();
            $table->json('prerequisites')->nullable();
            $table->boolean('is_self_paced')->default(false);
            $table->json('languages')->nullable();
            $table->json('syllabus')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('category_id');
            $table->index('level');
            $table->index('is_self_paced');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_templates');
    }
};
