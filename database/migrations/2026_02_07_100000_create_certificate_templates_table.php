<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('is_default')->default(false);
            $table->string('layout', 64)->default('classic');
            $table->text('header_text')->nullable();
            $table->text('body_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('institute_name')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
