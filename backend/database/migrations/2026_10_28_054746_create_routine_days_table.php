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
        Schema::create('routine_days', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('day_order');

            // Llave foránea hacia la tabla routines
            $table->foreignId('routine_id')
                  ->constrained('routines')
                  ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_days');
    }
};
