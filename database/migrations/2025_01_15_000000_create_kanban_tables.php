<?php

declare(strict_types=1);

use App\Enums\CardStatusEnum;
use App\Enums\CardTypeEnum;
use App\Enums\PriorityEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // Create boards table
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color code
            $table->boolean('is_active')->default(true);
            $table->boolean('system_protected')->default(true);
            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_id');
        });

        // Create board_user pivot table
        Schema::create('board_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->timestamps();

            $table->unique(['board_id', 'user_id']);
            $table->index(['board_id', 'role'], 'board_user_board_role_index');
        });

        // Create columns table
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color code
            $table->integer('order')->default(0);
            $table->integer('wip_limit')->nullable(); // Work in progress limit
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_id');
            $table->index(['board_id', 'is_active', 'order'], 'columns_board_active_order_index');
        });

        // Create cards table
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('column_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('card_type')->default(CardTypeEnum::TASK->value); // CardTypeEnum::class
            $table->string('priority')->default(PriorityEnum::MEDIUM->value); // PriorityEnum::class
            $table->string('status')->default(CardStatusEnum::ACTIVE->value); // CardStatusEnum::class
            $table->date('due_date')->nullable();
            $table->integer('order')->default(0);
            $table->schemalessAttributes('extra_attributes'); // For storing type-specific data
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index('branch_id');
            $table->index(['board_id', 'column_id'], 'cards_board_column_index');
            $table->index(['column_id', 'order'], 'cards_column_order_index');
            $table->index(['board_id', 'order'], 'cards_board_order_index');
            $table->index('due_date', 'cards_due_date_index');
        });

        // Create card_user pivot table
        Schema::create('card_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['assignee', 'reviewer', 'watcher'])->default('assignee');
            $table->timestamps();

            $table->unique(['card_id', 'user_id', 'role']);
            $table->index(['card_id', 'role'], 'card_user_card_role_index');
        });

        // Create card_history table
        Schema::create('card_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('column_id')->constrained()->onDelete('cascade');
            $table->string('action'); // moved, created, updated, etc.
            $table->text('description')->nullable();
            $table->schemalessAttributes('extra_attributes'); // For storing detailed change information
            $table->timestamps();

            $table->index('branch_id');
        });

        // Create card_flows table
        Schema::create('card_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('board_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_column_id')->constrained('columns')->onDelete('cascade');
            $table->foreignId('to_column_id')->constrained('columns')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->schemalessAttributes('extra_attributes'); // For storing flow conditions
            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_id');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('card_flows');
        Schema::dropIfExists('card_history');
        Schema::dropIfExists('card_user');
        Schema::dropIfExists('cards');
        Schema::dropIfExists('columns');
        Schema::dropIfExists('board_user');
        Schema::dropIfExists('boards');
    }
};
