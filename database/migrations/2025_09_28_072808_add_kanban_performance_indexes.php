<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // Add indexes for cards table
        Schema::table('cards', function (Blueprint $table) {
            $table->index(['board_id', 'column_id'], 'cards_board_column_index');
            $table->index(['column_id', 'order'], 'cards_column_order_index');
            $table->index(['board_id', 'order'], 'cards_board_order_index');
            $table->index('due_date', 'cards_due_date_index');
        });

        // Add indexes for columns table
        Schema::table('columns', function (Blueprint $table) {
            $table->index(['board_id', 'is_active', 'order'], 'columns_board_active_order_index');
        });

        // Add indexes for card_user pivot table
        Schema::table('card_user', function (Blueprint $table) {
            $table->index(['card_id', 'role'], 'card_user_card_role_index');
        });

        // Add indexes for board_user pivot table
        Schema::table('board_user', function (Blueprint $table) {
            $table->index(['board_id', 'role'], 'board_user_board_role_index');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        // Drop indexes for cards table
        Schema::table('cards', function (Blueprint $table) {
            $table->dropIndex('cards_board_column_index');
            $table->dropIndex('cards_column_order_index');
            $table->dropIndex('cards_board_order_index');
            $table->dropIndex('cards_due_date_index');
        });

        // Drop indexes for columns table
        Schema::table('columns', function (Blueprint $table) {
            $table->dropIndex('columns_board_active_order_index');
        });

        // Drop indexes for card_user pivot table
        Schema::table('card_user', function (Blueprint $table) {
            $table->dropIndex('card_user_card_role_index');
        });

        // Drop indexes for board_user pivot table
        Schema::table('board_user', function (Blueprint $table) {
            $table->dropIndex('board_user_board_role_index');
        });
    }
};
