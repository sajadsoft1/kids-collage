<?php

declare(strict_types=1);

use App\Enums\YesNoEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contactuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('follow_up')->default(YesNoEnum::NO->value);
            $table->string('name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->text('comment');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactuses');
    }
};
