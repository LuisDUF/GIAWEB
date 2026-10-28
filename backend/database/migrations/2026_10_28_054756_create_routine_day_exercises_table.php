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
        Schema::create('routine_day_exercises', function (Blueprint $table) {
            $table->id();
            $table->integer('order_index');

            // Llave foránea hacia routine_days
            $table->foreignId('routine_day_id')
                  ->constrained('routine_days')
                  ->cascadeOnDelete();

            // Llave foránea hacia exercises
            $table->foreignId('exercise_id')
                  ->constrained('exercises')
                  ->cascadeOnDelete();

            // Auto-referencia nullable para agrupar superseries
            $table->foreignId('superset_id')
                  ->nullable()
                  ->constrained('routine_day_exercises')
                  ->nullOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_day_exercises');
    }
};
