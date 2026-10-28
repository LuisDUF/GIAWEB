<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('routines', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->boolean('is_public')->default(false);
    $table->boolean('is_system_routine')->default(false);
    $table->boolean('esborrada')->default(false);

    $table->foreignUuid('user_id')
          ->nullable()
          ->constrained('profiles')
          ->nullOnDelete();

    $table->timestamps();

});
    }

    public function down(): void
    {
        Schema::dropIfExists('routines');
    }
};
