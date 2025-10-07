<?php

use App\Enums\BooleanEnum;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('parent_child', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'child_id')->constrained('users')->onDelete('cascade');

            $table->unique(['parent_id', 'child_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_child');
    }
};
