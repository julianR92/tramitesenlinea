<?php

namespace App\Http\Controllers;

use App\Publicidad;
use App\Persona;
use App\PublicidadDetalle;
use App\PublicidadLiquidacion;
use App\PublicidadConceptos;
use App\PublicidadNovedad;
use App\PublicidadAdjunto;

use App\Mail\MailNotificacion;

use App\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnvioNotificacion;
use App\PublicidadConfigNovedades;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use PDF;

include_once 'DadepGeneral.php';

class myObject
{
   public function __set($name, $value)
   {
      $this->{$name} = $value;
   }
}

class PublicidadAdmin extends Controller
{
   public function __construct()
   {
      //  $this->middleware('auth');
   }

   public function index()
   {
      dd('dd');
   }

   public function Administrar($modalidad)
   {
      $tipo = $r->modalidad;
      $dep = $r->dependencia;

      $sGrupos = [];
      $estados = ['ENVIADA', 'EN PROGRESO', 'PENDIENTE', 'APROBADA', 'RECHAZADA'];
      $nombres = ['Enviadas', 'En Progreso', 'Pendientes', 'Aprobadas', 'Rechazadas'];
      $i = 0;
      foreach ($estados as $estado) {
         $objeto = new myObject();
         $objeto->datos = DB::select(Publicidad::SqlEstado($estado, $tipo, $dep));
         $objeto->titulo = str_replace(' ', '', $estado);
         $objeto->cantidad = count($objeto->datos);
         $objeto->nombre = $nombres[$i];
         $objeto->activo = '';
         $sGrupos[] = $objeto;
         $i++;
      }
      $sGrupos[0]->activo = 'active';
      $porCerrar = DB::select(Publicidad::SqlXCerrar());
      $PORCERRAR = 0;
      if (!empty($porCerrar)) {
         $PORCERRAR = $porCerrar[0]->Cantidad;
      }
      return view('tramites.interior.publicidad.index1', compact('sGrupos', 'PORCERRAR', 'tipo'));
   }

   public function interior($modalidad)
   {
      $solicitudes = DB::select(Publicidad::getRadicados($modalidad, 'interior'));
      return view('tramites.interior.publicidad.index1', compact('solicitudes', 'modalidad'));

      // return $this->Administrar($modalidad, $dependencia);
   }

   public function planeacion()
   {
      $solicitudes = DB::select(Publicidad::getRadicados('TODAS', 'planeacion'));
      return view('tramites.interior.publicidad.index1', compact('solicitudes'));
   }

   public function salud(Request $r)
   {
      $solicitudes = DB::select(Publicidad::getRadicados('TODAS', 'salud'));
      return view('tramites.interior.publicidad.index1', compact('solicitudes'));
   }

   public function hacienda(Request $r)
   {
      $solicitudes = DB::select(Publicidad::getRadicados('TODAS', 'hacienda'));
      return view('tramites.interior.publicidad.index1', compact('solicitudes'));
   }

   public function transito(Request $r)
   {
      $r->modalidad = '%';
      $r->dependencia = 'TRANSITO';
      return $this->Administrar($r);
   }

   public function detalle($id)
   {
      $solicitud = Publicidad::findOrFail($id);
      $persona = Persona::Find($solicitud->PersonaId);

      $documento = PublicidadAdjunto::where('Radicado', $solicitud->radicado)->get();

      $documentos = [];
      if ($documento->count() > 0) {
         $documentos = $documento->getDictionary();
      }

      $novedad = PublicidadNovedad::where('SolicitudId', $solicitud->id)->get();
      $novedades = [];

      if ($novedad->count() > 0) {
         $novedades = $novedad->getDictionary();
      }

      $adjunto = PublicidadConceptos::where('publicidad_id', $id)
         ->get()
         ->first();

      $detalle = PublicidadDetalle::where('publicidad_id', $id)
         ->get()
         ->first();

      $vista = $this->vistas($solicitud->dependencia, $solicitud->modalidad);
      $vista = "tramites.interior.publicidad.detalle";

      $fecha_actual = date('d-m-Y');
      // $fecha_limite = date('d-m-Y', strtotime($fecha_actual . '+ 2 month'));

      $config_novedades = DB::select("SELECT estado_id AS estado_id,nov.id AS novedad_id,estado,titulo_estado,novedad,opcion,estado_sig,finaliza
      FROM publicidad_config_novedades AS nov
      INNER JOIN publicidad_config_estados AS est ON nov.estado_id=est.id
      WHERE estado_id=(SELECT id FROM publicidad_config_estados WHERE modalidad='$solicitud->modalidad' AND estado='$solicitud->estado_solicitud')AND dependencia='$solicitud->dependencia'");
      $fecha_limite = Carbon::now()->addDays(60)->format('Y-m-d');
      $salario_minimo =  Config::get('global.salario_minimo.' . date('Y'));
      return view($vista, compact('persona', 'solicitud', 'detalle', 'adjunto', 'novedades', 'documentos', 'config_novedades', 'fecha_limite', 'salario_minimo'));
   }

   public function AgregarNovedad(Request $req)
   {
      $solicitud = Publicidad::Find($req->SolicitudId);


      if (!empty($solicitud)) {

         DB::beginTransaction();

         try {
            $config_novedad = DB::select("SELECT estado,titulo_estado,dependencia,novedad,opcion,estado_sig,finaliza FROM publicidad_config_estados pce
            INNER JOIN publicidad_config_novedades AS pcn ON pce.id=pcn.estado_id  WHERE pcn.id=$req->novedad LIMIT 1;");

            $estado_sig = $config_novedad[0]->estado_sig;

            $estado_siguiente = DB::select("SELECT estado,titulo_estado,dependencia,novedad,opcion,estado_sig FROM publicidad_config_estados pce
            INNER JOIN publicidad_config_novedades AS pcn ON pce.id=pcn.estado_id  WHERE pce.id=$estado_sig LIMIT 1;");

            $novedad = new PublicidadNovedad();
            $novedad->NovedadTipo = $config_novedad[0]->opcion;
            $novedad->NovedadComentario = $req->observacion;
            $novedad->NovedadEstado = $config_novedad[0]->estado;
            $novedad->FuncionarioId = 1;
            $novedad->SolicitudId = $req->SolicitudId;
            $novedad->save();


            if (count($estado_siguiente) > 0) {
               if ($config_novedad[0]->finaliza == 1 && $config_novedad[0]->dependencia == "interior") {
                  $solicitud->fecha_inicio = $req->fecha_inicio;
                  $solicitud->fecha_fin = $req->fecha_fin;
                  $solicitud->dia_publicidad = $req->dif_dias_publicidad;
                  $solicitud->dias_restantes = $req->dif_dias_publicidad;

                  $solicitud->estado_solicitud = $estado_siguiente[0]->estado;
                  $solicitud->dependencia = $estado_siguiente[0]->dependencia;
                  $solicitud->novedad = $estado_siguiente[0]->novedad;
               } else {
                  $solicitud->estado_solicitud = $estado_siguiente[0]->estado;
                  $solicitud->dependencia = $estado_siguiente[0]->dependencia;
                  $solicitud->novedad = $estado_siguiente[0]->novedad;
               }
            } else {
               $resta = (int)$req->dia_publicidad - (int)$req->meses_pautar;
               $solicitud->dias_restantes = $resta;
               //colocar una condicion adicional de los nuevos dias, q si es cero finalice el estado
               if ($config_novedad[0]->finaliza == 1 && $config_novedad[0]->dependencia == "hacienda" && $resta <= 0) {
                  $solicitud->estado_solicitud = "finalizado";
                  $solicitud->dependencia = 'hacienda';
                  $solicitud->novedad = 'Proceso finalizado';
               }
            }
            $solicitud->save();

            $folder = 'documentos_publicidad/' . $solicitud->radicado;
            $personas = Persona::Find($solicitud->PersonaId);
            $liquidacion = '';
            foreach ($req->allFiles() as $key => $file) {
               $documentos = PublicidadAdjunto::where('radicado', $solicitud->radicado)->where('codigo_adjunto', $config_novedad[0]->estado)->get();
               if ($documentos->count() == 0) {
                  $documento = new PublicidadAdjunto;
                  $documento->publicidad_id = $solicitud->id;
                  $nombre_archivo = $config_novedad[0]->estado;
                  if ($config_novedad[0]->finaliza == 1 && $config_novedad[0]->dependencia == "hacienda") {
                     $documento->codigo_adjunto = "Resolucion" . '-' . $req->consecutivo;
                     $documento->nombre_adjunto = "Resolución hacienda";
                     $documento->ruta = $folder . "/resolucion" . '-' . $req->consecutivo . ".pdf";
                     $nombre_archivo = "/resolucion" . '-' . $req->consecutivo;
                     $generar_liquidacion = $this::generarLiquidacion($personas, $solicitud, $req);
                     $liquidacion = $generar_liquidacion;
                  } else {
                     $documento->codigo_adjunto = $config_novedad[0]->estado;
                     $documento->nombre_adjunto = $config_novedad[0]->titulo_estado;
                     $documento->ruta = $folder . '/' . $config_novedad[0]->estado . ".pdf";
                  }

                  $documento->radicado = $solicitud->radicado;
                  $documento->save();
                  $file->storeAs($folder, $nombre_archivo . ".pdf");
               }
            }

            if (($config_novedad[0]->finaliza == 1 && $config_novedad[0]->dependencia == "hacienda") || ($config_novedad[0]->finaliza == 1 && $config_novedad[0]->dependencia == "interior")) {
               PublicidadAdmin::sendMail($personas, $solicitud, $req->observacion, $documento->ruta, $liquidacion);
            } else {
               PublicidadAdmin::sendMail($personas, $solicitud, $req->observacion, 'NO');
            }



            DB::commit();
         } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
         }
      }

      return redirect('/tramites/hacienda/publicidad/');
   }

   public function downloadPdf($id, $consecutivo, $fecha, $total, $fecha_inicial, $fecha_final)
   {
      // set_time_limit(120);
      $solicitud = Publicidad::Find($id);
      $detalle = PublicidadDetalle::where('publicidad_id', $id)->get()[0];
      $persona = Persona::Find($solicitud->PersonaId);
      $salario_minimo =  Config::get('global.salario_minimo.' . date('Y'));
      $diferencia = $this->diferenciaFechas($fecha_inicial, $fecha_final);


      $pdf = PDF::loadView('tramites.publicidad.res_permanente', compact('solicitud', 'detalle', 'persona', 'consecutivo', 'fecha', 'total', 'fecha_inicial', 'fecha_final', 'salario_minimo', 'diferencia'));
      // return $pdf->download('liquidacion.pdf');
      return response($pdf->output(), 200)
         ->header('Content-Type', 'application/pdf')
         ->header('Content-Disposition', 'attachment; filename="liquidacion.pdf"');
   }

   private function generarLiquidacion($personas, $solicitud, $req)
   {
      $concepto = '';
      switch ($solicitud->modalidad) {
         case 'vallas':
            $concepto = 1;
            break;
      }
      $fecha_actual = date('d-m-Y');
      //sumo 1 mes
      //$fecha = date("d-m-Y", strtotime($fecha_actual . "+ 2 month"));
      $detalle = PublicidadDetalle::where('publicidad_id', '=', $solicitud->id)->first();
      $fecha = date('d-m-Y', strtotime($req->fecha_limite));
      $responsable = $personas->PersonaTipDoc == 'NIT' ? $personas->PersonaRazon : $personas->PersonaNombre . ' ' . $personas->PersonaApe;
      $sql = "SET NOCOUNT ON  EXEC [Pa_Publicidad] 1,'" . $responsable . "','" . $personas->PersonaDoc . "','" . $solicitud->radicado . "'," . date('Y') . ',' . $solicitud->id . ",'" . $detalle->direccion_elemento . "'," . (int) $req->valor_total . ",'" . $fecha . "','" . $req->tipo_liquidacion . "'," . $concepto;
      $conexion = DB::connection('sqlsrv');
      $resultado = $conexion->select($sql);
      $valor_m2 = round(((int)$req->salario_minimo * 4) / 48, 2);
      $liquidacion = new PublicidadLiquidacion();
      $liquidacion->publicidad_id = $req->SolicitudId;
      $liquidacion->tipo_liquidacion = $req->tipo_liquidacion;
      $liquidacion->salario_minimo = (int)$req->salario_minimo;
      $liquidacion->area_publicidad = $req->area_publicidad;
      $liquidacion->valor_m2 = $valor_m2;
      $liquidacion->valor_mensual = $req->valor_mensual;
      $liquidacion->fecha_liq_ini = $req->fecha_parcial_inicial;
      $liquidacion->fecha_liq_fin = $req->fecha_parcial_final;
      $liquidacion->meses_pautar = $req->meses_pautar;
      $liquidacion->valor_total = $req->valor_total;
      $liquidacion->consecutivo = $req->consecutivo;
      $liquidacion->fecha_consecutivo = $req->fecha_consecutivo;

      $liquidacion->fecha_limite = date('Y-m-d', strtotime($req->fecha_limite));
      //$liquidacion->url_pago = $resultado[0]->dirurl;
      $liquidacion->url_pago = $resultado[0]->dirurl;
      $liquidacion->save();
      return $liquidacion->url_pago;
   }

   public function Liquidacion(Request $req)
   {
      $solicitud = Publicidad::Find($req->SolicitudId);
      $detalle = PublicidadDetalle::where('publicidad_id', $req->SolicitudId)->get()[0];
      $persona = Persona::Find($solicitud->PersonaId);
      if ($req->tipo_liquidacion == "PERMANENTE") {
         $pdf = PDF::loadView('tramites.publicidad.res_permanente', compact('solicitud', 'detalle', 'persona'));
      } else {
         $pdf = PDF::loadView('tramites.publicidad.res_transitoria', compact('solicitud', 'detalle', 'persona'));
      }

      $nombre = $solicitud->radicado . '-Resolución.pdf';
      $folder = 'documentos_publicidad/' . $solicitud->radicado;
      $ruta = 'storage/' . $folder . '/' . $nombre;
      $pdf->save($ruta);

      $doc = new PublicidadAdjunto();
      $doc->publicidad_id = $req->SolicitudId;
      $doc->DocNombre = $nombre;
      //   $doc->DocTitulo = 'Resolucion del radicado '.$solicitud->radicado;
      $doc->DocTitulo = 'Resolución de liquidación';
      $doc->DocRuta = $ruta . '?1';
      $doc->Radicado = $solicitud->radicado;
      $doc->save();

      $novedad = new PublicidadNovedad();
      $novedad->NovedadComentario = 'Realizar el pago del impuesto de publicidad exterior visual';
      $novedad->NovedadEstado = 'PENDIENTE';
      $novedad->NovedadTipo = 'Generación liquidación impuesto';
      $novedad->SolicitudId = $req->SolicitudId;
      $novedad->FuncionarioId = 1;
      $novedad->save();

      $solicitud = Publicidad::Find($req->SolicitudId);
      $solicitud->estado_solicitud = 'PENDIENTE';
      $solicitud->dependencia = 'HACIENDA';
      $solicitud->save();

      $per = Persona::Find($solicitud->PersonaId);

      $solicitud->Comentario = $novedad->NovedadComentario;
      $solicitud->NovedadTipo = $novedad->NovedadTipo;
      $solicitud->NovedadEstado = $novedad->NovedadEstado;
      $solicitud->PerNombre = $per->PersonaNombre . ' ' . $per->PersonaApe;

      $docs = [$novedad->NovedadTipo];
      //$documentos = PublicidadAdmin::CargarDoc($req, $docs, $solicitud->radicado);

      $user = DadepGeneral::GetUser();

      $auditoria = Auditoria::create([
         'usuario' => $user,
         'proceso_afectado' => 'Radicado-Publicidad-' . $solicitud->radicado,
         'tramite' => 'PUBLICIDAD EXTERIOR VISUAL',
         'radicado' => $solicitud->radicado,
         'accion' => 'update a estado ' . $solicitud->estado_solicitud . ' ' . $novedad->NovedadTipo . ' ' . $novedad->NovedadEstado,
         'observacion' => $req->NovedadComentario,
      ]);

      $publicidadDetalle = PublicidadDetalle::where('publicidad_id', '=', $solicitud->id)->first();
      $responsable = $solicitud->nombre_responsable . ' ' . $solicitud->apellido_responsable;
      $concepto = '';
      switch ($solicitud->modalidad) {
         case 'VALLAS':
            $concepto = 1;
            break;
         case 'PENDONES':
            if ($solicitud->sub_modalidad == 'AVISOS DE IDENTIFICACION DE ESTABLECIMIENTOS COMERCIALES') {
               $concepto = 10;
            }
            if ($solicitud->sub_modalidad == 'IDENTIFICACION PROYECTOS INMOBOLIARIOS') {
               $concepto = 14;
            }
            if ($solicitud->sub_modalidad == 'AVISOS TIPO COLOMBINA') {
               $concepto = 15;
            }
            break;
         case 'MURALES':
            $concepto = 4;
            break;
         case 'PASACALLES':
            $concepto = 6;
            break;
         case 'PUBLICIDAD AEREA':
            $concepto = 8;
            break;
         case 'MOVIL':
            $concepto = 9;
            break;
      }

      $fecha_actual = date('d-m-Y');
      //sumo 1 mes
      //$fecha = date("d-m-Y", strtotime($fecha_actual . "+ 2 month"));
      $fecha = date('d-m-Y', strtotime($req->fecha_limite));
      $sql = "SET NOCOUNT ON  EXEC [Pa_Publicidad] 1,'" . $responsable . "','" . $solicitud->numero_documento . "','" . $solicitud->radicado . "'," . date('Y') . ',' . $solicitud->id . ",'" . $publicidadDetalle->ubicacion_aviso . "'," . (int) $req->valor_total . ",'" . $fecha . "','" . $req->tipo_liquidacion . "'," . $concepto;
      $conexion = DB::connection('sqlsrv');
      $resultado = $conexion->select($sql);
      //dd($sql);
      $liquidacion = new PublicidadLiquidacion();
      $liquidacion->publicidad_id = $req->SolicitudId;
      $liquidacion->tipo_liquidacion = $req->tipo_liquidacion;
      $liquidacion->salario_minimo = 1300000;
      $liquidacion->area_publicidad = $req->area_publicidad;
      $liquidacion->valor_m2 = $req->valor_m2;
      $liquidacion->valor_mensual = $req->valor_mensual;
      $liquidacion->meses_pautar = $req->meses_pautar;
      $liquidacion->valor_total = $req->valor_total;

      $liquidacion->fecha_limite = date('Y-m-d', strtotime($req->fecha_limite));
      $liquidacion->url_pago = $resultado[0]->dirurl;
      $liquidacion->save();

      $documentos = $ruta;
      PublicidadAdmin::sendMail($per, $solicitud, 'tramites.PublicidadAdmin.CorreoSol', false, $documentos, $resultado[0]->dirurl);

      echo $novedad->NovedadEstado;
      echo $solicitud->dependencia;
      // return redirect('/tramites/interior/publicidad/detalle/' . $solicitud->id);
      return redirect('/tramites/interior/publicidad/detalle');
   }

   private function estado($tipo, $estado, $modalidad)
   {
      $estados = [];
      $estados['COMPLETO1'] = 'EN PROGRESO';
      $estados['INCOMPLETO1'] = 'PENDIENTE';
      $estados['RECHAZADO1'] = 'RECHAZADA';
      $estados['FAVORABLE2'] = 'EN PROGRESO';
      $estados['NOFAVORABLE2'] = 'EN PROGRESO';
      $estados['FAVORABLE3'] = 'EN PROGRESO';
      $estados['NOFAVORABLE3'] = 'EN PROGRESO';
      $estados['FAVORABLE4'] = 'EN PROGRESO';
      $estados['NOFAVORABLE4'] = 'EN PROGRESO';
      $estados['VIABLE5'] = 'EN PROGRESO';
      $estados['VIABLE5VALLAS'] = 'PENDIENTE';
      $estados['NOVIABLE5'] = 'RECHAZADA';
      $estados['COMPLETO6'] = 'EN PROGRESO';
      $estados['INCOMPLETO6'] = 'PENDIENTE';
      $estados['LIQUIDADO7'] = 'EN PROGRESO';
      $estados['APROBADO8'] = 'APROBADA';

      $estado = str_replace(' ', '', $estado) . $tipo;
      $estado1 = $estado . str_replace(' ', '', $modalidad);
      echo "$estado : $estado1 <br>";
      if (array_key_exists($estado1, $estados)) {
         return $estados[$estado1];
      }
      return $estados[$estado];
   }

   private function destino($tipo, $estado, $modalidad)
   {
      $estados = [];
      $estados['COMPLETO1'] = 'PLANEACION';
      $estados['COMPLETO1PASACALLES'] = 'INTERIOR';
      $estados['COMPLETO1PUBLICIDADAEREA'] = 'SALUD';
      $estados['COMPLETO1MOVIL'] = 'TRANSITO';
      $estados['INCOMPLETO1'] = 'INTERIOR';
      $estados['RECHAZADO1'] = 'INTERIOR';
      $estados['FAVORABLE2'] = 'SALUD';
      $estados['NOFAVORABLE2'] = 'INTERIOR';
      $estados['FAVORABLE3'] = 'SALUD';
      $estados['NOFAVORABLE3'] = 'INTERIOR';
      $estados['FAVORABLE4'] = 'INTERIOR';
      $estados['NOFAVORABLE4'] = 'INTERIOR';
      $estados['VIABLE5'] = 'HACIENDA';
      $estados['VIABLE5VALLAS'] = 'INTERIOR';
      $estados['NOVIABLE5'] = 'INTERIOR';
      $estados['COMPLETO6'] = 'HACIENDA';
      $estados['INCOMPLETO6'] = 'INTERIOR';
      $estados['LIQUIDADO7'] = 'INTERIOR';
      $estados['APROBADO8'] = 'INTERIOR';

      $estados['VIABLE5MURALES'] = 'INTERIOR';
      $estados['VIABLE5PUBLICIDADAEREA'] = 'HACIENDA';
      $estados['VIABLE5MOVIL'] = 'HACIENDA';

      $estado = str_replace(' ', '', $estado) . $tipo;
      $estado1 = $estado . str_replace(' ', '', $modalidad);
      if (array_key_exists($estado1, $estados)) {
         return $estados[$estado1];
      }
      return $estados[$estado];
   }

   private function vistas($dependencia, $modalidad)
   {
      $estados = [];
      $estados['INTERIOR'] = 'tramites.interior.publicidad.detalle';
      $estados['INTERIORVALLAS'] = 'tramites.interior.publicidad.detalle';

      $estado = str_replace(' ', '', $dependencia);
      $estado1 = str_replace(' ', '', $dependencia) . str_replace(' ', '', $modalidad);
      if (array_key_exists($estado1, $estados)) {
         return $estados[$estado1];
      } elseif (array_key_exists($estado, $estados)) {
         return $estados[$estado];
      }
      return 'tramites.interior.publicidad.detalle';
   }

   public static function CargarDoc(Request $req, $titulos, $radicado, $publicidad_id)
   {
      $cant = count($titulos);
      $cargados = true;
      $utimos = [];
      for ($i = 0; $i < $cant; $i++) {
         $titulo = str_replace(' ', '', $titulos[$i]);
         $nombre = $radicado . '-' . $titulo . '.pdf';
         $folder = 'documentos_publicidad/' . $radicado;
         $ruta = 'storage/' . $folder . '/' . $nombre;

         if ($req->$titulo || $req->$titulo != null) {
            $g = $req->file($titulo)->storeAs($folder, $nombre);
         } else {
            if ($req->documento0 || $req->documento0 != null) {

               $g = $req->file('documento0')->storeAs($folder, $nombre);
            } else {
               $g = false;
            }
         }
         if ($g) {
            $docs = PublicidadAdjunto::where('DocNombre', $nombre)->get();
            if ($docs->count() > 0) {
               $array = $docs->getDictionary();
               $doc = reset($array);
               $idx = substr($doc->DocRuta, -1);
               if (is_numeric($idx)) {
                  $idx++;
               } else {
                  $idx = 1;
               }
            } else {
               $doc = new PublicidadAdjunto();
               $idx = 1;
            }
            $doc->publicidad_id = $publicidad_id;
            $doc->DocNombre = $nombre;
            $doc->DocTitulo = $titulos[$i];
            $doc->DocRuta = $ruta . '?' . $idx;
            $doc->Radicado = $radicado;
            $doc->save();
            $ultimos[] = $ruta;
         } else {
            $cargados = false;
            $ultimos[] = 'NO';
         }
      }
      return $ultimos;
   }

   public static function sendMail($persona, $publicidad, $comentarios, $doc = 'NO', $liquidacion = '')
   {
      $detalle_correo = [
         'nombre_completo' => $persona->PersonaTip == 'Juridica' ? $persona->PersonaRazon : $persona->PersonaNombre . ' ' . $persona->PersonaApe,
         'radicado' => $publicidad->radicado,
         'Subject' => 'Solucitud publicidad exterior visual',
         'documento' => $doc,
         'liquidacion' => $liquidacion,
         'publicidad' => $publicidad,
         'comentarios' => $comentarios
      ];

      $correo_funcionario = ['interior-publicidad@bucaramanga.gov.co'];

      if ($publicidad->dependencia == 'planeacion') {
         $correo_funcionario[] = 'planeacion-publicidad@bucaramanga.gov.co';
      }

      if ($publicidad->dependencia == 'salud') {
         $correo_funcionario[] = 'salud-publicidad@bucaramanga.gov.co';
      }

      Mail::to($persona->PersonaMail)
         ->queue(new MailNotificacion($detalle_correo, 'tramites.PublicidadAdmin.CorreoSol'));

      Mail::to($correo_funcionario)
         ->queue(new MailNotificacion($detalle_correo, 'tramites.PublicidadAdmin.CorreoFun'));
   }
   public static function diferenciaFechas($fechaInicio, $fechaFin)
{
    // Convertir las fechas a objetos Carbon
    $inicio = Carbon::parse($fechaInicio);
    $fin = Carbon::parse($fechaFin);

    // Calcular la diferencia en meses
    $diferenciaMeses = $inicio->diffInMonths($fin);
    $restante = $inicio->copy()->addMonths($diferenciaMeses);

    // Calcular la diferencia en días
    $diferenciaDias = $restante->diffInDays($fin)+1;

    // Ajustar si los días adicionales forman exactamente un mes
    if ($diferenciaDias >= 30) {
        $diferenciaMeses++;
        $diferenciaDias = 0;
    }

    // Formatear el resultado
    $resultado = [];
    if ($diferenciaMeses > 0) {
        $resultado[] = $diferenciaMeses . ' ' . ($diferenciaMeses === 1 ? 'mes' : 'meses');
    }
    if ($diferenciaDias > 0) {
        $resultado[] = $diferenciaDias . ' ' . ($diferenciaDias === 1 ? 'día' : 'días');
    }

    return implode(' y ', $resultado);
}
}
