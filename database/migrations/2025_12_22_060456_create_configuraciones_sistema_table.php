<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuraciones_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique(); // 'sistema_suscripciones_activo'
            $table->text('valor')->nullable();
            $table->timestamps();
        });

        // Insertar valor por defecto
        DB::table('configuraciones_sistema')->insert([
            'clave' => 'sistema_suscripciones_activo',
            'valor' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones_sistema');
    }
};