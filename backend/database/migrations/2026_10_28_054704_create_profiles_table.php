<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Cambiado a UUID como Primary Key
            $table->string('username')->unique();
            $table->string('gender')->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('consecutive_log_days')->default(0);
            $table->boolean('premium')->default(false);
            $table->boolean('esadmin')->default(false);
            $table->string('password')->nullable();

            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
