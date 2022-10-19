<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliaLactanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familia_lactante', function (Blueprint $table) {
            $table->id();
            $table->string('nit',15)->comment("NIT empresa");
            $table->string('razon_social',100)->comment("nombre de la empresa");
            $table->string('direccion', 150)->comment("direccion de la empresa");
            $table->string('barrio', 100)->comment("barrio de la empresa");
            $table->string('telefono_empresa',10)->comment("telefono empresa");
            $table->string('correo_electronico', 60)->comment("correo de empresa");
            $table->string('nom_representante', 40)->comment("Nombre del representante legal");
            $table->string('numero_mujeres_empresa', 4)->comment("Numero de mujeres que trabajan en la empresa");
            $table->string('numero_mujeres_gestantes', 4)->comment("Numero de mujeres que estan gestantes en la empresa");
            $table->string('numero_mujeres_lactantes', 4)->comment("Numero de mujeres que estan lactando en la empresa");
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
        Schema::dropIfExists('familia_lactante');
    }
}
