<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateDocEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_update_doc', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evento_id')->unsigned();
            $table->string('adj_logisticaEvento', 500)->comment('Ruta del oficio donde explica la Logistica del evento')->nullable();
            $table->string('adj_cedulaRes', 500)->comment('Ruta Adjunto cédula de ciudadanía (persona natural sea el responsable del evento) o certificado existencia y representación legal (responsable del evento sea una persona jurídica)')->nullable();
            $table->string('adj_autorizacionTra', 500)->comment('Ruta del Autorizacion de transito es opcional dependiendo del sitio')->nullable();
            $table->string('adj_contratoArr', 500)->comment('Autorización escrita o contrato de arrendamiento suscrito por el propietario, administrador, arrendador o poseedor legal del inmueble destinado para desarrollar el evento. Si el evento es en espacio público: Viabilidad escrita por la Oficina de Parque y Zonas Verdes de la Secretaria de Infraestructura para el uso y ocupación del espacio público de la zona. (Calle 35 No. 10 – 43 Edificio Fase I Piso 4).')->nullable();
            $table->string('adj_conceptoCMGRD', 500)->comment('Ruta de Concepto técnico emitido por el comité de Gestión del Riesgo Municipal, del plan de emergencia y contingencia, que deberá constar por escrito incluyendo las recomendaciones. (No continuar con el trámite de los demás requisitos sin haber obtenido primero la certificación favorable emitida por el Comité).')->nullable();
            $table->string('adj_conceptoTecAmb', 500)->comment('Concepto técnico sanitario y ambiental, emitido por la Subsecretaria de Medio Ambiente de la Secretaria de Salud Municipal. (Calle 35 # 10-43 Fase I Piso 2).')->nullable();
            $table->string('adj_certificadoPONAL', 500)->comment('Certificado de conocimiento del Evento por parte del Comando Operativo de Seguridad Ciudadana de la Policía Metropolitana')->nullable();
            $table->string('adj_certificadoBomberos', 500)->comment('ruta de Acreditar el cumplimiento de las condiciones de seguridad de acuerdo al protocolo establecido por el Cuerpo de Bomberos de Bucaramanga.')->nullable();
            $table->string('adj_hospitalaria', 500)->comment('Constancia del servicio de prestación pre hospitalaria con un organismo de socorro, Defensa Civil o Cruz Roja')->nullable();
            $table->string('adj_poliza', 500)->comment('Ruta Póliza de Responsabilidad Civil Extracontractual por cien (100) salarios mínimos legales  mensuales vigentes, con una vigencia igual al término de duración de la autorización y un (1) mes  más, con el fin de garantizar la realización del evento y de amparar los posibles perjuicios que se causen a terceros con ocasión de la actividad.')->nullable();
            $table->string('adj_publicidad',500)->comment('Pago de impuesto publicidad exterior visual o paz y salvo de publicidad exterior visual de que no van a exhibir ningún tipo de publicidad. (Oficina de publicidad exterior visual- Secretaria del Interior) (Calle 35 No. 10 – 43 Edificio Fase I Piso 3).')->nullable();
            $table->string('adj_certificadoEMAB', 500)->comment('Certificado de servicio de aseo emitido por la EMAB. En relación con las áreas aledañas al sitio del evento (Anexar recibo de pago original).')->nullable();
            $table->timestamps();
        });

        Schema::table('eventos_update_doc', function (Blueprint $table) {
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->index('evento_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos_update_doc');
    }
}
