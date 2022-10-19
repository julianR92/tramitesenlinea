<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableCertificacionDiscapacidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificacion_discapacidad', function (Blueprint $table) {
            $table->id();
            $table->string('radicado',40)->comment("Numero de radicado Unico");
            $table->string('nom_responsable', 40)->comment("Nombre del responsable del la persona con discapacidad");
            $table->string('ape_responsable', 40)->comment("Apellidos del responsable de la persona con discapacidad");
            $table->string('ide_responsable', 20)->comment("Numero de documento de identidad");
            $table->string('email_responsable', 40)->comment("Correo electronico del responsable de la solicitud");
            $table->string('tel_responsable', 15)->comment("Telefono del responsable de la solicitud");
            $table->string('nom_solicitante', 40)->comment("Nombre del solicitante del certificado");
            $table->string('ape_solicitante', 40)->comment("Apellido del Solicitante del certificado");
            $table->string('tipo_identificacion_sol', 50)->comment("Tipo de identificacion del solicitante del certificado");
            $table->string('ide_solicitante', 15)->comment("Numero de identificacion del solicitante");
            $table->string('correo_solicitante', 60)->comment("correo electronico solicitante")->nullable();
            $table->string('tel_solicitante', 15)->comment("Barrio de la empresa o razon social");
            $table->string('direccion_solicitante', 15)->comment("Direccion del solicitante");
            $table->string('barrio_solicitante', 100)->comment("Barrio Solicitante");
            $table->string('ciudad_id', 10)->comment("Codigo de la ciudad FK de la tabla municipio");
            $table->string('adj_recibo', 500)->comment("Ruta de recibo de servico publico");
            $table->string('adj_documento', 500)->comment('Ruta de copia de documento de identidad');
            $table->string('adj_historia_clinica', 500)->comment('Ruta de copia de documento de identidad');
            $table->enum('estado_solicitud',['ENVIADA','PENDIENTE','ACTUALIZADA','RADICADA','APROBADA','RECHAZADA'])->comment('Estado de la solicitud');
            $table->string('observaciones_solicitud', 500)->comment('Observaciones de la solicitud')->nullable();
            $table->date('fecha_actuacion')->comment('ultima fecha de actuacion de la solicitud')->nullable();
            $table->date('fecha_pendiente')->comment('Fecha para solicitudes en estado pendiente')->nullable();
            $table->string('act_documentos', 3)->comment('Estado de actualizacion de documentos')->nullable();
            $table->string('adj_certificado',500)->comment('Documento de respuesta planeacion')->nullable();          
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
        Schema::dropIfExists('certificacion_discapacidad');
    }
}
