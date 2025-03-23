<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detalles_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_academico_id')->constrained('cursos')->onDelete('cascade'); // Relación con curso académico
            $table->foreignId('unidad_formativa_id')->constrained('unidades_formativas')->onDelete('cascade'); // Relación con unidad formativa
            $table->string('codigo'); // Código de la unidad formativa
            $table->string('nombre'); // Nombre de la unidad formativa
            $table->date('inicio')->nullable(); // Fecha de inicio (nullable)
            $table->date('fin')->nullable(); // Fecha de fin (nullable)
            $table->date('Examen0')->nullable(); // Fecha de fin (nullable)
            $table->date('ExamenF')->nullable(); // Fecha de fin (nullable)
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('detalles_curso');
    }
    
};
