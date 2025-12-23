<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\DiscountTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('code')->unique()->index()->comment('Discount code');
            $table->string('type')->index()->default(DiscountTypeEnum::PERCENTAGE->value)->comment('percent or amount');
            $table->decimal('value', 10, 2)->comment('Discount value (percentage or fixed amount)');
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->nullOnDelete()->comment('Specific user (null = for all users)');
            $table->decimal('min_order_amount', 10, 2)->default(0)->comment('Minimum order amount to apply discount');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->comment('Maximum discount amount (for percentage type)');
            $table->integer('usage_limit')->nullable()->comment('Total usage limit (null = unlimited)');
            $table->integer('usage_per_user')->default(1)->comment('Usage limit per user');
            $table->integer('used_count')->default(0)->comment('Times this discount has been used');
            $table->dateTime('starts_at')->nullable()->comment('Discount start date');
            $table->dateTime('expires_at')->nullable()->comment('Discount expiration date');
            $table->boolean('is_active')->default(BooleanEnum::ENABLE->value);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('branch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
