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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_curso_id'); // Hace referencia a 'id' de 'alumnos_curso'
            $table->unsignedBigInteger('unidad_formativa_id'); // Relación con unidad formativa
            $table->decimal('nota', 5, 2)->nullable(); // Nota del alumno
            $table->timestamps();
    
            // Definir la clave foránea 'alumno_curso_id' que hace referencia a 'id' de 'alumnos_curso'
            $table->foreign('alumno_curso_id')
                  ->references('id')
                  ->on('alumnos_curso')
                  ->onDelete('cascade');
    
            // Definir la clave foránea 'unidad_formativa_id' que hace referencia a 'id' de 'unidades_formativas'
            $table->foreign('unidad_formativa_id')
                  ->references('id')
                  ->on('unidades_formativas')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
