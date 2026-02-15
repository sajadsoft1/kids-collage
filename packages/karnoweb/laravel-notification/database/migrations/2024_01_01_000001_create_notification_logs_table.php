<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_logs';

        Schema::create($tableName, function (Blueprint $table): void {
            $table->id();
            $table->string('event')->index();
            $table->string('channel')->index();
            $table->morphs('notifiable');
            $table->string('notification_class')->nullable();
            $table->string('status')->default('queued');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_logs';
        Schema::dropIfExists($tableName);
    }
};
