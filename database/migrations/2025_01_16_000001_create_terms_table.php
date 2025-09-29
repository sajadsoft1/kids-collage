<?php

declare(strict_types=1);

use App\Enums\TermStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->json('languages')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default(TermStatus::DRAFT->value);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
