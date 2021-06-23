<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriaConsultaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('auditoria_consulta', function (Blueprint $table) {
            $table->id();
            $table->string('nom_solicitante', 40);
            $table->string('ape_solicitante', 40);
            $table->string('identificacion_solicitante', 20);
            $table->string('email_solicitante', 60);
            $table->string('numero_busqueda', 20);
            $table->string('tratamiento_datos', 3)->nullable();
            $table->string('acepto_terminos',3)->nullable();
            $table->string('confirmo_mayorEdad', 3)->nullable();
            $table->string('compartir_informacion', 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auditoria_consulta');
    }
}
