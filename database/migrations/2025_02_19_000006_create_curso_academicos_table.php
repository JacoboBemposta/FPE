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
        Schema::create('curso_academicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade'); // Relacionado con la tabla cursos
            $table->foreignId('academia_id')->constrained('users')->onDelete('cascade');
            $table->string('municipio', 100)->nullable();;
            $table->string('provincia', 100)->nullable();;
            $table->date('inicio')->nullable();
            $table->date('fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_academicos');
    }
};