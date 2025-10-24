<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('path')->nullable();
            $table->string('title');
            $table->integer('order')->default(0);
            $table->text('description')->nullable();
            $table->schemalessAttributes('extra_attributes');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('course_session_template_resource', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')
                ->constrained('resources')
                ->cascadeOnDelete();
            $table->foreignId('course_session_template_id')
                ->constrained('course_session_templates')
                ->cascadeOnDelete()
                ->name('cst_resource_cst_id_foreign');
            $table->timestamps();

            $table->unique(['resource_id', 'course_session_template_id'], 'resource_cst_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_session_template_resource');
        Schema::dropIfExists('resources');
    }
};
