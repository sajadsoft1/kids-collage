<?php

declare(strict_types=1);

use App\Enums\BranchStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->text('languages')->nullable();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('status')->default(BranchStatusEnum::ACTIVE->value);
            $table->boolean('is_default')->default(false);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
