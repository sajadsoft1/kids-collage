<?php

declare(strict_types=1);

use App\Enums\TicketPriorityEnum;
use App\Enums\TicketStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('subject');
            $table->string('department');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('closed_by')->nullable()->constrained('users');
            $table->string('status')->default(TicketStatusEnum::OPEN->value);
            $table->string('priority')->default(TicketPriorityEnum::HIGH->value);
            $table->string('key')->unique();
            $table->timestamps();

            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
