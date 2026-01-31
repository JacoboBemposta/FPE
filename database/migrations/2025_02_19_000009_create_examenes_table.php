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
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            // Cada examen pertenece a una oferta específica de un curso (curso académico)
            $table->foreignId('curso_academicos_id')->constrained('curso_academicos')->onDelete('cascade');
            $table->string('examen'); // Por ejemplo: "Examen Parcial 1", "Examen Final", etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen');
    }
};
