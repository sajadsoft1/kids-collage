<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('permissions');
            $table->schemalessAttributes('extra_attributes');
            $table->boolean('show')->nullable()->default(true);
            $table->timestamps();
        });

        //        Cache::forget('global-setting');
    }
    
    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
