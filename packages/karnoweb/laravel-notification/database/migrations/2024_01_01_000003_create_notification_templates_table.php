<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_templates';

        Schema::create($tableName, function (Blueprint $table): void {
            $table->id();
            $table->string('event')->index();
            $table->string('channel')->index();
            $table->string('locale', 12)->default('fa');
            $table->string('subject')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->json('cta')->nullable();
            $table->json('placeholders')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['event', 'channel', 'locale']);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_templates';
        Schema::dropIfExists($tableName);
    }
};
