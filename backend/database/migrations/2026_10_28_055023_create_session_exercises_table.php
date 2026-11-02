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
        Schema::create('session_exercises', function (Blueprint $table) {
            $table->id();
            $table->integer('total_sets')->default(0);
            $table->decimal('max_weight_lifted', 8, 2)->nullable();
            $table->decimal('min_weight_lifted', 8, 2)->nullable();
            $table->boolean('is_skipped')->default(false);
            $table->boolean('is_adhoc')->default(false);
            $table->foreignId('superset_id')
                  ->nullable()
                  ->constrained('session_exercises')
                  ->nullOnDelete();
            $table->text('notas')->nullable();

            // Llave foránea hacia workout_sessions
            $table->foreignId('session_id')
                  ->constrained('workout_sessions')
                  ->cascadeOnDelete();

            // Llave foránea hacia exercises
            $table->foreignId('exercise_id')
                  ->constrained('exercises')
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_exercises');
    }
};
