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
        Schema::create('curso_modulo', function (Blueprint $table) {
            $table->id();
            
            // Definir la clave foránea de 'curso_id'
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
    
            // Definir la clave foránea de 'modulo_id'
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
    
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_modulo');
    }
};
