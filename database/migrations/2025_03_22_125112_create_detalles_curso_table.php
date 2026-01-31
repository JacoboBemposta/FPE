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
            $table->foreignId('curso_academico_id')->constrained('curso_academicos')->onDelete('cascade'); // Relación con curso académico
            $table->foreignId('unidad_formativa_id')->nullable()->constrained('unidades_formativas')->onDelete('cascade');
            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');
            $table->date('inicio')->nullable(); // Fecha de inicio (nullable)
            $table->date('fin')->nullable(); // Fecha de fin (nullable)
            $table->date('Examen0')->nullable(); // Fecha de fin (nullable)
            $table->date('ExamenF')->nullable(); // Fecha de fin (nullable)
            $table->timestamps();

            $table->unique(['curso_academico_id', 'modulo_id', 'unidad_formativa_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('detalles_curso');
    }
    
};
