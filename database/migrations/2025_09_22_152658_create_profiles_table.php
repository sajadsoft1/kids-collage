<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('id_number')->nullable();
            $table->string('national_code')->unique()->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender',30)->nullable(); // GenderEnum::class
            $table->string('address')->nullable();
            $table->string('phone',15)->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone',15)->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone',15)->nullable();
            $table->string('religion',30)->nullable(); // ReligionEnum::class
            $table->double('salary')->default(0);
            $table->double('benefit')->default(0);
            $table->date('cooperation_start_date')->nullable();
            $table->date('cooperation_end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
