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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            // La familia profesional es obligatoria
            $table->foreignId('familia_profesional_id')->constrained('familias_profesionales')->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->unsignedBigInteger('horas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
