<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetrolineaUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caracterizacion_metrolinea', function (Blueprint $table) {
            $table->id();
            $table->string('numero_solicitud', 50)->comment('Numero unico de solicitud');            
            $table->enum('tipo_poblacion',['ESTUDIANTE','DEPORTISTA','ADULTO MAYOR', 'PERSONAS CON DISCAPACIDAD']);
            $table->string('nombre_usuario', 40)->comment("Nombre de usuario de metrolinea");
            $table->string('apellido_usuario', 40)->comment("Apellido de usuario de metrolinea");
            $table->date('fecha_nacimiento')->comment("Fecha de nacimiento de usuario de metrolinea");
            $table->string('edad', 3)->comment('Edad del usuario');
            $table->string('estado_civil', 40)->comment('Estado civil de usuario de metrolinea');
            $table->string('nombre_acudiente', 100)->comment('Nombre de acudiente cuando usuario no es mayor de edad')->nullable();
            $table->string('nivel_estudios',50)->comment('nivel de estudios de usuario de metrolinea');
            $table->string('tipo_documento', 20)->comment('Tipo documento de usuario');
            $table->string('documento_usuario', 20)->comment('Numero de identificacion de usuario');
            $table->string('sexo', 20)->comment('Genero de Usuario');
            $table->string('orientacion_sexual', 60)->comment('orientacion_sexual');
            $table->string('telefono_usuario', 15)->comment('telefono de usuario');
            $table->string('email_usuario', 60)->comment('correo del usuario');
            $table->string('ciudad', 60)->comment('Ciudad del usuario por defecto es Bucaramnaga');
            $table->string('barrio', 60)->comment('Barrio donde reside el usuario');
            $table->string('corregimiento', 60)->comment('Corregimiento donde vive el usuario')->nullable();
            $table->string('direccion_usuario', 120)->comment('direccion usuario');
            $table->string('institucion_educativa', 100)->comment('institucion del usuario')->nullable();
            $table->string('estrato_socioeconomico', 3)->comment('Estrato de usuario');
            $table->string('trabaja_actualmente', 4)->comment('Confirmacion si tiene trabajo actualmente');
            $table->string('ruta_frecuente', 10)->comment('Rutas frecuentes abordadas por el usuario');
            $table->string('discapacidad', 40)->comment('Tipo de discapacidad')->nullable();           
            $table->string('adj_documentoIdentidad', 500)->comment('ruta donde se guarda el documento de identidad')->nullable();
            $table->string('adj_certiVecindad', 500)->comment('Ruta de certificado de vecindad')->nullable();
            $table->string('adj_certificadoEstudio', 500)->comment('certificado de estudio')->nullable();
            $table->enum('estado_solicitud',['ENVIADA','APROBADA','RECHAZADA'])->comment('Estado de la solicitud')->nullable();
            $table->timestamp('fecha_actuacion')->nullable(); 
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
        Schema::dropIfExists('caracterizacion_metrolinea');
    }
}
