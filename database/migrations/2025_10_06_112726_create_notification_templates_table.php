<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(BooleanEnum::ENABLE->value);
            $table->text('languages')->nullable();
            $table->string('name')->nullable();
            $table->string('channel')->default('sms');
            $table->text('message_template')->nullable();
            $table->json('inputs')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
