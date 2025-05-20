<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrestamoPlanos extends Model
{
   protected $table = 'prestamo_planos';
   protected $primaryKey = 'id';


   protected $fillable = [
      "radicado",
      "fecha_solicitud",
      "nombre_solicitante",
      "documento_identificacion",
      "direccion_solicitante",
      "correo_electronico",
      "telefono",
      "numero_licencia",
      "tipo_licencia",
      "modalidad_licencia",
      "numero_predial",
      "direccion_predio",
      "barrio",
      "numero_folio_matricula_inmobiliaria",
      "propietario_predio",
      "nombre_constructor",
      "fecha_aproximada_documentacion",
      "documentos_requeridos",
      "motivo_destinacion",
      "observaciones",
      "estado_solicitud",
      "documento_certificado_libertad",
      "acepta_terminos",
      "autoriza_notificacion",
   ];

   public function tipoLicencia()
   {
      return $this->belongsTo('App\PlanosTipoLicencia', 'tipo_licencia');
   }

   public function modalidadLicencia()
   {
      return $this->belongsTo('App\PlanosModalidades', 'modalidad_licencia');
   }
}
