<?php

use Facade\Ignition\Tabs\Tab;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorizacionParqueaderosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizacion_parqueaderos', function (Blueprint $table) {
            $table->id();
            $table->string('radicado',40)->comment("Numero de radicado Unico");
            $table->string('nom_solicitante', 40)->comment("Nombre del solicitante");
            $table->string('ape_solicitante', 40)->comment("Apellidos del solicitante");
            $table->string('tipo_documento', 20)->comment("Tipo de documento de identidad");
            $table->string('identificacion_solicitante', 40)->comment("Numero de identificacion del solicitante");
            $table->string('direccion_solicitante', 120)->comment("Direccion de la persona Solicitante");
            $table->string('barrio_solicitante', 50)->comment("Barrio de persona solicitante");
            $table->string('tel_solicitante', 15)->comment("Telefono de la persona solicitante");
            $table->string('email_responsable', 50)->comment("Email del responsable");
            $table->string('nombre_empresa', 60)->comment("Nombre o razon social de la empresa");
            $table->string('direccion_empresa', 120)->comment("Direccion de la empresa o razon social");
            $table->string('barrio_empresa', 50)->comment("Barrio de la empresa o razon social");
            $table->string('tel_empresa', 15)->comment("Telefono de empresa o razon social");
            $table->string('adjunto_camara', 500)->comment("Ruta de documento de Camara de comercio");
            $table->string('adjunto_rut', 500)->comment("Ruta de documento RUT");
            $table->string('adjunto_planos', 500)->comment("Ruta de documento planos aprobados de construccion");
            $table->string('adjunto_licencia', 500)->comment('Ruta de documento licencia de construccion');
            $table->enum('estado_solicitud',['ENVIADA','PENDIENTE','REVISION-PLANEACION','RESPUESTA-PLANEACION','APROBADA','RECHAZADA'])->comment('Estado de la solicitud');
            $table->string('observaciones_solicitud', 500)->comment('Observaciones de la solicitud')->nullable();
            $table->date('fecha_actuacion')->comment('ultima fecha de actuacion de la solicitud')->nullable();
            $table->date('fecha_pendiente')->comment('Fecha para solicitudes en estado pendiente')->nullable();
            $table->string('act_documentos', 3)->comment('Estado de actualizacion de documentos')->nullable();
            $table->string('adjunto_resPlaneacion',500)->comment('Documento de respuesta planeacion')->nullable();
            $table->string('adjunto_respuesta', 500)->comment('Acto administrativo de respuesta rechazada o aprobado')->nullable();
            $table->string('tratamiento_datos', 5)->comment('Verificacion de tratamiento de datos personales');
            $table->string('acepto_terminos', 5)->comment("Acepta Terminos y condiciones");
            $table->string("confirmo_mayorEdad", 5)->comment("Confirma que es Mayor de edad, y esta diciendo informacion veraz");
            $table->string("compartir_informacion",5)->comment("Acepta Compartir Informacion con otras entidades")->nullable();
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
        Schema::dropIfExists('categorizacion_parqueaderos');
    }
}
