<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->foreignId('certificate_template_id')
                ->nullable()
                ->after('course_template_level_id')
                ->constrained('certificate_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('course_templates', function (Blueprint $table) {
            $table->dropForeign(['certificate_template_id']);
        });
    }
};
