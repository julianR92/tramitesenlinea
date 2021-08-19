<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditoriaParqueaderoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria_parqueaderos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parqueadero_id')->unsigned();
            $table->string('radicado',40)->comment("Numero de radicado Unico")->nullable();
            $table->string('nom_solicitante', 40)->comment("Nombre del solicitante")->nullable();
            $table->string('ape_solicitante', 40)->comment("Apellidos del solicitante")->nullable();
            $table->string('tipo_documento', 20)->comment("Tipo de documento de identidad")->nullable();
            $table->string('identificacion_solicitante', 40)->comment("Numero de identificacion del solicitante")->nullable();
            $table->string('direccion_solicitante', 120)->comment("Direccion de la persona Solicitante")->nullable();
            $table->string('barrio_solicitante', 50)->comment("Barrio de persona solicitante")->nullable();
            $table->string('tel_solicitante', 15)->comment("Telefono de la persona solicitante")->nullable();
            $table->string('email_responsable', 50)->comment("Email del responsable")->nullable();
            $table->string('nombre_empresa', 60)->comment("Nombre o razon social de la empresa")->nullable();
            $table->string('direccion_empresa', 120)->comment("Dirección de la empresa o razon social")->nullable();
            $table->string('barrio_empresa', 50)->comment("Barrio de la empresa o razon social")->nullable();
            $table->string('tel_empresa', 15)->comment("Telefono de empresa o razon social")->nullable();
            $table->string('adjunto_camara', 500)->comment("Ruta de documento de Camara de comercio")->nullable();
            $table->string('adjunto_rut', 500)->comment("Ruta de documento RUT")->nullable();
            $table->string('adjunto_planos', 500)->comment("Ruta de documento planos aprobados de construccion")->nullable();
            $table->string('adjunto_licencia', 500)->comment('Ruta de documento licencia de construccion')->nullable();
            $table->enum('estado_solicitud',['ENVIADA','PENDIENTE','REVISION-PLANEACION','RESPUESTA-PLANEACION','APROBADA','RECHAZADA'])->comment('Estado de la solicitud')->nullable();
            $table->string('observaciones_solicitud', 500)->comment('Observaciones de la solicitud')->nullable();
            $table->date('fecha_actuacion')->comment('ultima fecha de actuacion de la solicitud')->nullable();       
            $table->string('adjunto_resPlaneacion',500)->comment('Documento de respuesta planeacion')->nullable();
            $table->timestamps();
        });

        Schema::table('auditoria_parqueaderos', function (Blueprint $table) {
            $table->foreign('parqueadero_id')->references('id')->on('categorizacion_parqueaderos');
            $table->index('parqueadero_id');

        });

        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auditoria_parqueaderos');
    }
}
