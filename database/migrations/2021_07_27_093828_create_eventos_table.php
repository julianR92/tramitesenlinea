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
            $table->bigInteger('tipo_id')->unsigned();
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
            $table->string('adj_logisticaEvento', 500)->comment('Ruta del oficio donde explica la Logistica del evento')->nullable();
            $table->string('adj_cedulaRes', 500)->comment('Ruta Adjunto cédula de ciudadanía (persona natural sea el responsable del evento) o certificado existencia y representación legal (responsable del evento sea una persona jurídica)')->nullable();
            $table->string('adj_autorizacionTra', 500)->comment('Ruta del Autorizacion de transito es opcional dependiendo del sitio')->nullable();
            $table->string('adj_contratoArr', 500)->comment('Autorización escrita o contrato de arrendamiento suscrito por el propietario, administrador, arrendador o poseedor legal del inmueble destinado para desarrollar el evento. Si el evento es en espacio público: Viabilidad escrita por la Oficina de Parque y Zonas Verdes de la Secretaria de Infraestructura para el uso y ocupación del espacio público de la zona. (Calle 35 No. 10 – 43 Edificio Fase I Piso 4).')->nullable();
            $table->string('adj_conceptoCMGRD', 500)->comment('Ruta de Concepto técnico emitido por el comité de Gestión del Riesgo Municipal, del plan de emergencia y contingencia, que deberá constar por escrito incluyendo las recomendaciones. (No continuar con el trámite de los demás requisitos sin haber obtenido primero la certificación favorable emitida por el Comité).')->nullable();
            $table->string('adj_conceptoTecAmb', 500)->comment('Concepto técnico sanitario y ambiental, emitido por la Subsecretaria de Medio Ambiente de la Secretaria de Salud Municipal. (Calle 35 # 10-43 Fase I Piso 2).');
            $table->string('adj_certificadoPONAL', 500)->comment('Certificado de conocimiento del Evento por parte del Comando Operativo de Seguridad Ciudadana de la Policía Metropolitana')->nullable();
            $table->string('adj_certificadoBomberos', 500)->comment('ruta de Acreditar el cumplimiento de las condiciones de seguridad de acuerdo al protocolo establecido por el Cuerpo de Bomberos de Bucaramanga.')->nullable();
            $table->string('adj_hospitalaria', 500)->comment('Constancia del servicio de prestación pre hospitalaria con un organismo de socorro, Defensa Civil o Cruz Roja')->nullable();
            $table->string('adj_poliza', 500)->comment('Ruta Póliza de Responsabilidad Civil Extracontractual por cien (100) salarios mínimos legales  mensuales vigentes, con una vigencia igual al término de duración de la autorización y un (1) mes  más, con el fin de garantizar la realización del evento y de amparar los posibles perjuicios que se causen a terceros con ocasión de la actividad.')->nullable();
            $table->string('adj_publicidad',500)->comment('Pago de impuesto publicidad exterior visual o paz y salvo de publicidad exterior visual de que no van a exhibir ningún tipo de publicidad. (Oficina de publicidad exterior visual- Secretaria del Interior) (Calle 35 No. 10 – 43 Edificio Fase I Piso 3).')->nullable();
            $table->string('adj_certVigilancia',500)->comment('Anexar certificación expedida por Empresa de Vigilancia y Seguridad Privada y/o Empresa de Logística legalmente constituida, que garantice la prestación del servicio de seguridad y vigilancia del evento.')->nullable();
            $table->string('adj_certificadoEMAB', 500)->comment('Certificado de servicio de aseo emitido por la EMAB. En relación con las áreas aledañas al sitio del evento (Anexar recibo de pago original).')->nullable();
            $table->enum('estado_solicitud',['ENVIADA','PENDIENTE','APROBADA','RECHAZADA'])->comment('Estado de la solicitud');
            $table->string('observaciones_solicitud', 500)->comment('Observaciones de la solicitud')->nullable();
            $table->date('fecha_actuacion')->comment('ultima fecha de actuacion de la solicitud')->nullable();
            $table->date('fecha_pendiente')->comment('Fecha para solicitudes en estado pendiente')->nullable();            
            $table->string('adjunto_respuesta', 500)->comment('Acto administrativo de respuesta rechazada o aprobado')->nullable();
            $table->string('tratamiento_datos', 5)->comment('Verificacion de tratamiento de datos personales');
            $table->string('acepto_terminos', 5)->comment("Acepta Terminos y condiciones");
            $table->string("confirmo_mayorEdad", 5)->comment("Confirma que es Mayor de edad, y esta diciendo informacion veraz");
            $table->string("compartir_informacion",5)->comment("Acepta Compartir Informacion con otras entidades")->nullable();
            $table->timestamps();
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->foreign('tipo_id')->references('id')->on('tipo_evento');
            $table->index('tipo_id');

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
