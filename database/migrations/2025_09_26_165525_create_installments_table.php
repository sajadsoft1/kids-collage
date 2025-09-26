<?php

declare(strict_types=1);

use App\Enums\InstallmentMethodEnum;
use App\Enums\InstallmentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->index()->constrained('payments')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->index();
            $table->string('method')->index()->default(InstallmentMethodEnum::ONLINE->value);
            $table->string('status')->index()->default(InstallmentStatusEnum::PENDING->value);
            $table->string('transaction_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
