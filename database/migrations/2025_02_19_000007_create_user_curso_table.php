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
        Schema::create('user_curso', function (Blueprint $table) {
            // No necesitas un ID autonumérico en tablas pivote
            $table->foreignId('curso_academico_id')->constrained('curso_academicos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Clave primaria compuesta (mejor práctica para tablas pivote)
            $table->primary(['curso_academico_id', 'user_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_curso');
    }
};
