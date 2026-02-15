<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_preferences';

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table): void {
            $table->id();
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('event')->index();
            $table->string('channel')->index();
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id'], 'kn_pref_notifiable_index');
            $table->unique(['notifiable_type', 'notifiable_id', 'event', 'channel'], 'kn_pref_notifiable_event_channel');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        $tableName = config('karnoweb-notification.table_prefix', 'karnoweb_') . 'notification_preferences';
        Schema::dropIfExists($tableName);
    }
};
