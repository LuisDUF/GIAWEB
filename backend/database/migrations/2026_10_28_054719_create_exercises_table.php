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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('animacion')->nullable();
            $table->string('equipment_type')->nullable();
            $table->string('maquina')->nullable();
            $table->boolean('con_asiento')->nullable();
            $table->boolean('con_respaldo')->nullable();

            // Llave foránea hacia muscle_groups
            $table->foreignId('muscle_group_id')
                  ->constrained('muscle_groups')
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
