<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->index('tipo_id');
            $table->string('nom_responsable', 40)->comment("Nombre del responsable del evento");
            $table->string('ape_responsable', 40)->comment("Apellido del responsable del evento");
            $table->string('tipo_documento',20)->comment('Tipo de documento del responsable del evento');
            $table->string('ide_responsable',20)->comment('Numero de documento del responsable del evento');
            $table->string('dir_responsable', 120)->comment('Direccion del organizador del evento');
            $table->string('tel_responsable', 15)->comment('Telefono del responsable del evento');
            $table->string('email_responsable',60)->comment('Email de responsable del evento');
            $table->date('fecha_evento')->comment('Fecha del evento')->nullable();
            $table->string('hora_inicio', 15)->comment('Hora de inicio del evento')->nullable();
            $table->string('hora_fin', 15)->comment('Hora fin del evento')->nullable();
            $table->string('ubicacion_evento',150)->comment('direccion completa del evento')->nullable();
            $table->string('barrio_evento', 60)->comment('Barrio del evento')->nullable();
            $table->string('descripcion_evento', 400)->comment('descripcion completa del evento')->nullable();
            $table->string('logistica_evento', 500)->comment('Logistica del evento')->nullable();
            $table->string('nomTra_responsable', 40)->comment("Nombre del responsable del tramite")->nullable();
            $table->string('apeTra_responsable', 40)->comment('Apellido del responsable del evento')->nullable();
            $table->string('adj_autorizacionTra', 500)->comment('Ruta del Autorizacion de transito es opcional dependiendo del sitio')->nullable();
            $table->string('adj_contratoArr', 500)->comment('Ruta del contrato de arrendamiento es opcional dependiendo del sitio')->nullable();
            $table->string('adj_conceptoCMGRD', 500)->comment('Ruta de Concepto técnico emitido por el comité de Gestión del Riesgo Municipal, del plan de emergencia y contingencia, que deberá constar por escrito incluyendo las recomendaciones. (No continuar con el trámite de los demás requisitos sin haber obtenido primero la certificación favorable emitida por el Comité).')->nullable();
            $table->string('adj_certificadoPONAL', 500)->comment('Certificado de conocimiento del Evento por parte del Comando Operativo de Seguridad Ciudadana de la Policía Metropolitana')->nullable();
            $table->string('adj_certificadoBomberos', 500)->comment('ruta de Acreditar el cumplimiento de las condiciones de seguridad de acuerdo al protocolo establecido por el Cuerpo de Bomberos de Bucaramanga.')->nullable();
            $table->string('adj_HVmanipulador', 500)->comment('Hoja de vida del manipulador, (documento de identidad y carnet), donde certifique la experiencia,  con certificación del curso.')->nullable();
            $table->string('adj_poliza', 500)->comment('Ruta Póliza de Responsabilidad Civil Extracontractual por cien (100) salarios mínimos legales  mensuales vigentes, con una vigencia igual al término de duración de la autorización y un (1) mes  más, con el fin de garantizar la realización del evento y de amparar los posibles perjuicios que se causen a terceros con ocasión de la actividad.')->nullable();
            $table->string('adj_cartaCompromiso',500)->comment('Carta de Compromiso por parte de los organizadores del evento donde certifique que en la zona donde se efectué la quema solo permanecerán los operarios y la exhibición se realizara en un radio de por lo menos cuarenta (40) metros de distancia de cualquier edificación o vía pública y a 20 metros de distancia de líneas telefónicas y postes de alumbrado.')->nullable();
            $table->string('adj_certificadoExt',500)->comment('Certificado en donde se contemple disponibilidad como mínimo de tres (3) extintores de agua a presión de 2.5 galones cada uno y en perfectas condiciones de uso.) metros de distancia de cualquier edificación o vía pública y a 20 metros de distancia de líneas telefónicas y postes de alumbrado.')->nullable();
            $table->string('adj_certificadoEMAB', 500)->comment('Certificado de servicio de aseo emitido por la EMAB. En relación con las áreas aledañas al sitio del evento (Anexar recibo de pago original).')->nullable();
           


           

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
        Schema::dropIfExists('eventos');
    }
}
