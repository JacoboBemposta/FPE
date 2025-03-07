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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('address')->nullable(); // Solo para academias
            $table->string('imagen')->nullable(); // Avatar para usuarios, logo para academias
            $table->string('titulacion')->nullable(); // Archivo de titulación para profesores
            $table->unsignedBigInteger('num_cursos')->default(0); // Cursos creados por academias o profesores
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
