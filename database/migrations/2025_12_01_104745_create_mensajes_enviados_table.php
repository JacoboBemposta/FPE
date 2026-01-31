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
        Schema::create('emails_enviados', function (Blueprint $table) {
            $table->id();
            
            // Información del remitente
            $table->foreignId('remitente_id')->nullable()->change();
            $table->string('remitente_tipo'); // 'academia' o 'profesor'
            $table->string('remitente_email');
            $table->foreignId('curso_id')->nullable();
            $table->string('remitente_nombre');
            
            // Información del destinatario
            $table->string('destinatario_email');
            $table->string('destinatario_tipo')->nullable(); // 'academia' o 'profesor'
            

            $table->string('contexto'); // 'docente_a_academia' o 'academia_a_docente'
            
            // Estado
            $table->boolean('enviado')->default(true);
            $table->text('error')->nullable();
            
            // Auditoría
            $table->string('ip', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emails_enviados');
    }
};
