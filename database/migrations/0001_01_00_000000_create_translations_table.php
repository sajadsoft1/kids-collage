<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');
            $table->string('key');
            $table->text('value');
            $table->string('locale');
            $table->unique([
                'translatable_id',
                'translatable_type',
                'key',
                'locale',
            ]);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
