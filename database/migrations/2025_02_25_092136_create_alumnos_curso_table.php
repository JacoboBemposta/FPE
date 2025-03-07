<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnosCursoTable extends Migration
{
    public function up()
    {
        Schema::create('alumnos_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_academico_id')->constrained('curso_academicos')->onDelete('cascade'); // Relación con curso_academicos
            $table->string('dni', 15)->unique();
            $table->string('nombre');
            $table->string('email')->unique();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('alumnos_curso');
    }
}
