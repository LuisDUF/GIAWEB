<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('session_sets', function (Blueprint $table) {
            $table->id();
            $table->integer('set_number');
            $table->decimal('weight_kg', 6, 2);
            $table->integer('reps');
            $table->decimal('rpe', 3, 1)->nullable();
            $table->string('set_type')->default('effective');
            $table->integer('sub_order')->nullable();

            // Llave foránea hacia session_exercises con eliminación en cascada
            $table->foreignId('session_exercise_id')
                  ->constrained('session_exercises')
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_sets');
    }
};
