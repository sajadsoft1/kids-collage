<?php

declare(strict_types=1);

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->index()->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('scheduled_date')->nullable();
            $table->string('type')->index()->default(PaymentTypeEnum::ONLINE->value);
            $table->string('status')->index()->default(PaymentStatusEnum::PENDING->value);
            $table->text('note')->nullable();
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();

            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
