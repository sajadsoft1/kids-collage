<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('discount_id')->nullable()->index()->constrained('discounts')->nullOnDelete();
            $table->decimal('pure_amount', 10, 2)->default(0)->comment('Amount before discount');
            $table->decimal('discount_amount', 10, 2)->default(0)->comment('Discount amount applied');
            $table->decimal('total_amount', 10, 2)->default(0)->comment('Final amount after discount');
            $table->string('status')->index()->default(OrderStatusEnum::PENDING->value);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
