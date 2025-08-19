<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slider_references', function (Blueprint $table) {
            $table->foreignId('slider_id')->constrained()->cascadeOnDelete();
            $table->morphs('morphable');
            $table->primary([
                'slider_id',
                'morphable_type',
                'morphable_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slider_references');
    }
};
