<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EspectaculosPublicos extends Model
{   
    protected $connection = 'sqlsrv';
    protected $table = 'espectaculos_publicos';
    protected $primaryKey = 'id';

    protected $fillable=[
        "evento_id",
        "radicado",
        "tipo_persona",
        "tipo_identificacion",
        "numero_identificacion",
        "direccion_notificacion",
        "barrio_notificacion",
        "nombre_o_razon",
        "telefono_movil",
        "estado_civil",
        "email_responsable",
        "nombre_evento",
        "fecha_inicio_evento",
        "hora_inicio",
        "hora_fin",
        "fecha_fin_evento",
        "lugar_evento",
        "descripcion_evento",
        "numero_poliza_cheque",
        "tipo_garantia",
        "valor_poliza_cheque",
        "adj_RUT",
        "adj_documentoident",
        "adj_camara_comercio",
        "adj_certificacion_boleteria_emitida",
        "adj_certificacion_boleteria_vendida",
        "adj_documentoGarantia",
        "adj_acta_reunion",
        "adj_acto_administrativo",
        "adj_acto_revocatorio",
        "adj_solicitud",
        "estado_solicitud",
        "fecha_actuacion",
        "observaciones",
        "estado_documentos",
        "tratamiento_datos",
        "acepto_terminos",
        "confirmo_mayorEdad",
        "compartir_informacion",
        "created_at"
        

      ];
}
