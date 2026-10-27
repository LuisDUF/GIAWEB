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
    Schema::table('session_sets', function (Blueprint $table) {
        // Usamos el nombre exacto de la restricción en PostgreSQL
        $table->dropForeign('session_sets_session_exercise_id_fkey');

        // Volvemos a crearla con onDelete('cascade')
        $table->foreign('session_exercise_id')
              ->references('id')
              ->on('session_exercises')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_sets', function (Blueprint $table) {
            // Revertir los cambios si haces un rollback
            $table->dropForeign(['session_exercise_id']);

            $table->foreign('session_exercise_id')
                  ->references('id')
                  ->on('session_exercises'); // Sin cascade
        });
    }
};
