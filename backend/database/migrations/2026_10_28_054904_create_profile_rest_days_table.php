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
        Schema::create('profile_rest_days', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('profile_id')
                  ->constrained('profiles')
                  ->cascadeOnDelete();

            // Llave foránea hacia la tabla week_days
            $table->foreignId('week_day_id')
                  ->constrained('week_days')
                  ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_rest_days');
    }
};
