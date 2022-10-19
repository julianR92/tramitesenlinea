<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Publicidad;
use App\Auditoria;
use App\PublicidadDetalle;
use App\PublicidadConceptos;
use App\PgirhDetalle;
use App\PgirhReporte;
use App\PgirhEmpresa;
use App\Barrio;
use App\Parametro;
use App\CertificacionDiscapacidad;
use App\Exports\ReportesPgirh;
use App\FamiliaLactante;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionPublicidad;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Mail\NotificacionDiscapacidad;
use Illuminate\Support\Facades\Session;

class SaludController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth',['except' => ['indexFamilia', 'storeFamilia','confirmacion', 'end']]);
   }

   public function index()
   {
      return view('tramites.salud.index');
   }

   // public function publicidadIndex()
   // {
   //    $sEnviadas = Publicidad::where('estado_solicitud', 'REVISION-CONCEPTOS-SALUD')->get();
   //    $count_enviadas = $sEnviadas->count();
   //    return view('tramites.salud.publicidad.listar_solicitudes', compact('sEnviadas', 'count_enviadas'));
   // }

   // public function publicidadDetalle($id)
   // {
   //    $solicitud = Publicidad::findOrFail($id);
   //    $detalle = PublicidadDetalle::join('barrio', 'barrio.codigo', '=', 'publicidad_detalle.barrio_aviso')->where('publicidad_id', $id)->get()->first();
   //    return view('tramites.salud.publicidad.detalle', compact('solicitud', 'detalle'));
   // }

   // public function publicidadUpdate(Request $request)
   // {
   //    $datos = Publicidad::findOrFail($request->id);

   //    switch ($request->modalidad) {
   //       case 'VALLAS':

   //          $this->validate($request, [
   //             "observacion_solicitud" => 'required',
   //             "adj_concepto_salud" => 'required'
   //          ]);

   //          if ($request->adj_concepto_salud || $request->adj_concepto_salud != null) {
   //             $adj_concepto_salud =  $request->file('adj_concepto_salud')->storeAs('documentos_publicidad/' . $datos->radicado, 'adj_concepto_salud-' . $datos->radicado . '.pdf');
   //             $adj_concepto_salud_rut = 'storage/documentos_publicidad/' . $datos->radicado . '/adj_concepto_salud-' . $datos->radicado . '.pdf';
   //          } else {
   //             $adj_concepto_salud = null;
   //          }

   //          $date = date('Y-m-d');
   //          $date_30 = date("Y-m-d", strtotime($date . "+15 Weekday"));

   //          $detalleCorreo = [
   //             'nombres' => 'Francia Milena Zuluaga Tangarife',
   //             'mensaje' => $request->observacion_solicitud,
   //             'Subject' => 'Envio Concepto Técnico Publicidad Exterior N°' . $datos->radicado,
   //             'documento' => 'NO',
   //             'fecha_pendiente' => $date_30,
   //             'radicado'  => $datos->radicado,
   //             'estado' => 'FUNCIONARIO'
   //          ];

   //          // actualizar datos
   //          $datos->estado_solicitud = 'VIABILIDAD-PERMISO';
   //          $datos->observacion_solicitud = $request->observacion_solicitud;
   //          $datos->fecha_actuacion = $date;

   //          //$correo_responsable = ['fzuluaga@bucaramanga.gov.co', 'pdiaz@bucaramanga.gov.co'];
   //          $correo_responsable = ['julianrincon9230@gmail.com', 'ojrincon@bucaramanga.gov.co'];

   //          if ($datos->save()) {
   //             $adjunto = PublicidadConceptos::where('publicidad_id', $request->id)->update(['adj_concepto_salud' => $adj_concepto_salud_rut]);
   //             //auditoria
   //             $auditoria = Auditoria::create([
   //                'usuario' => $request->username,
   //                'proceso_afectado' => 'Radicado-' . $datos->radicado,
   //                'tramite' => 'PUBLICIDAD EXTERIOR',
   //                'radicado' => $datos->radicado,
   //                'accion' => 'update a estado REVISION-CONCEPTOS-SALUD',
   //                'observacion' => $request->observacion_solicitud
   //             ]);

   //             Mail::to($correo_responsable)->queue(new NotificacionPublicidad($detalleCorreo));
   //             Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
   //             return redirect()->route('salud.publicidad.index');
   //          } else {
   //             Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
   //          }

   //          break;
   //       case 'PUBLICIDAD AEREA':
   //          $this->validate($request, [
   //             "observacion_solicitud" => 'required',
   //             "adj_concepto_salud" => 'required'
   //          ]);

   //          if ($request->adj_concepto_salud || $request->adj_concepto_salud != null) {
   //             $adj_concepto_salud =  $request->file('adj_concepto_salud')->storeAs('documentos_publicidad/' . $datos->radicado, 'adj_concepto_salud-' . $datos->radicado . '.pdf');
   //             $adj_concepto_salud_rut = 'storage/documentos_publicidad/' . $datos->radicado . '/adj_concepto_salud-' . $datos->radicado . '.pdf';
   //          } else {
   //             $adj_concepto_salud = null;
   //          }

   //          $date = date('Y-m-d');
   //          $date_30 = date("Y-m-d", strtotime($date . "+15 Weekday"));

   //          $detalleCorreo = [
   //             'nombres' => 'Francia Milena Zuluaga Tangarife',
   //             'mensaje' => $request->observacion_solicitud,
   //             'Subject' => 'Envio viabilidad concepto técnico Publicidad Exterior N°' . $datos->radicado,
   //             'documento' => 'NO',
   //             'fecha_pendiente' => $date_30,
   //             'radicado'  => $datos->radicado,
   //             'estado' => 'FUNCIONARIO'
   //          ];

   //          // actualizar datos
   //          $datos->estado_solicitud = 'VIABILIDAD-PERMISO';
   //          $datos->observacion_solicitud = $request->observacion_solicitud;
   //          $datos->fecha_pendiente_salud = null;
   //          $datos->fecha_actuacion = $date;

   //          //$correo_responsable = ['fzuluaga@bucaramanga.gov.co', 'pdiaz@bucaramanga.gov.co'];
   //          $correo_responsable = [ 'fhernandez@bucaramanga.gov.co'];

   //          if ($datos->save()) {
   //             $adjunto=new PublicidadConceptos;
   //             $adjunto->publicidad_id=$request->id;
   //             $adjunto->adj_concepto_salud=$adj_concepto_salud_rut;
   //             $adjunto->save();
   //             //auditoria
   //             $auditoria = Auditoria::create([
   //                'usuario' => $request->username,
   //                'proceso_afectado' => 'Radicado-' . $datos->radicado,
   //                'tramite' => 'PUBLICIDAD EXTERIOR',
   //                'radicado' => $datos->radicado,
   //                'accion' => 'update a estado VIABILIDAD-PERMISO',
   //                'observacion' => $request->observacion_solicitud
   //             ]);

   //             Mail::to($correo_responsable)->queue(new NotificacionPublicidad($detalleCorreo));
   //             Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
   //             return redirect()->route('salud.publicidad.index');
   //          } else {
   //             Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
   //          }
   //          break;

   //       default:
   //          # code...
   //          break;
   //    }
   // }

   //pgirh
   public function indexEmpresas(){
      
      $datos_empresa = PgirhEmpresa::join('actividad_economica', 'actividad_economica.id', '=' ,'empresas.actividad_id')->select('empresas.razon_social', 'empresas.nit', 'empresas.representante_legal','empresas.correo_electronico', 'empresas.telefono','actividad_economica.descripcion','empresas.direccion_empresa', 'empresas.barrio', 'empresas.created_at', 'empresas.id')->orderBy('empresas.id','desc')->get();

      return view('tramites.salud.pgirh.resultado', compact('datos_empresa'));     


   }

   public function reportesEmpresas($id){
    
      $reportes = PgirhReporte::join('empresas', 'empresas.id','=','reporte.empresa_id')->select('empresas.razon_social', 'empresas.nit', 'reporte.id','reporte.gestor_residuos','reporte.nombre_solicitante', 'reporte.year', 'reporte.semestre', 'reporte.ruta_acta_disposicion', 'reporte.created_at')->where('reporte.empresa_id', $id)->orderBy('reporte.id', 'desc')->get();
   
      return view('tramites.salud.pgirh.detalle', compact('reportes'));

   }

   public function excelDetalle($id,$empresa,$nit,$gestor){

      return Excel::download(new ReportesPgirh($id,$empresa,$nit,$gestor), 'Reporte-'.$id.'-'.$empresa.'.xlsx');

   }

   //FUNCIONES DISCAPACIDAD
   
   public function indexDiscapacidad(){

    $sEnviadas = CertificacionDiscapacidad::where('estado_solicitud', 'ENVIADA')->get();
    $sPendientes = CertificacionDiscapacidad::where('estado_solicitud', 'PENDIENTE')->get();
    $sActualizadas = CertificacionDiscapacidad::where('estado_solicitud', 'ACTUALIZADA')->get();
    $sRadicadas = CertificacionDiscapacidad::where('estado_solicitud', 'RADICADA')->get();
    $sAprobadas = CertificacionDiscapacidad::where('estado_solicitud', 'APROBADA')->get();
    $sRechazadas = CertificacionDiscapacidad::where('estado_solicitud', 'RECHAZADA')->get();
    $count_enviadas = $sEnviadas->count();
    $count_pendientes = $sPendientes->count();
    $count_actualizadas = $sActualizadas->count();
    $count_radicadas = $sRadicadas->count();
    $count_aprobadas = $sAprobadas->count();
   $count_rechazadas = $sRechazadas->count();

        return view('tramites.salud.discapacidad.index', compact('sEnviadas', 'sActualizadas', 'sPendientes', 'sRadicadas', 'sAprobadas', 'sRechazadas', 'count_enviadas', 'count_pendientes', 'count_actualizadas', 'count_radicadas', 'count_aprobadas', 'count_rechazadas'));

   }

   public function discapacidadDetalle($id)
   {
       $solicitud = CertificacionDiscapacidad::findOrFail($id);

       return view('tramites.salud.discapacidad.detalle', compact('solicitud'));
   }

   public function discapacidadUpdate(Request $request)
    {

        $datos = CertificacionDiscapacidad::findOrFail($request->id);

        if ($request->estado_solicitud == 'PENDIENTE') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
            ]);


            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = date("Y-m-d", strtotime($date . "+15 Weekday"));


            $detalleCorreo = [
                'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Documentos Pendientes Solicitud de Autorizacion de la certificacion de discapacidad N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,
                'id'=> Crypt::encrypt($request->id)
            ];

            // actualizar datos
            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            $datos->fecha_pendiente = $date_30;
            $datos->act_documentos = null;

            if ($datos->save()) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado' => 'Radicado-' . $datos->radicado,
                    'tramite'=>'CERTIFICACION DE DISCAPACIDAD',
                    'radicado'=> $datos->radicado,
                    'accion' => 'update a estado ' . $request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud

                ]);

                Mail::to($datos->email_responsable)->queue(new NotificacionDiscapacidad($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('salud.discapacidad.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
                return redirect()->route('salud.discapacidad.index');
            }
        } elseif ($request->estado_solicitud == 'RADICADA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = NULL;
           
            $detalleCorreo = [
               'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
               'mensaje' => $request->observaciones_solicitud,
               'Subject' => 'Solicitud Radicada de Autorizacion de la certificacion de discapacidad N°' . $datos->radicado,
               'documento' => 'NO',
               'fecha_pendiente' => $date_30,
               'radicado'  => $datos->radicado,
               'estado' => $request->estado_solicitud,
               'id'=> Crypt::encrypt($request->id)
           ];

            // actualizar datos
            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;           
            $datos->act_documentos = null;           

            

            if ($datos->save()) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado' => 'Radicado-' . $datos->radicado,
                    'tramite'=>'CERTIFICACION DE DISCAPACIDAD',
                    'radicado'=> $datos->radicado,
                    'accion' => 'update a estado ' . $request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud

                ]);

                Mail::to($datos->email_responsable)->queue(new NotificacionDiscapacidad($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('salud.discapacidad.index');
            } else {
                Alert::error('Error', 'Ha ocurrido un error al registrar la actualización de la solicitud');
                return redirect()->route('salud.discapacidad.index');
            }
        }  
        elseif ($request->estado_solicitud == 'APROBADA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
                "documento_respuesta" => 'required'
            ]);

            $date = date('Y-m-d');
            $date_30 = null;

            //mover documento a storage
            $adjunto1 = $request->file('documento_respuesta')->storeAs('certificaciones_discapacidad/' . $datos->radicado, 'Autorizacion-certificacion-discapacidad-' . $datos->radicado . '.pdf');

            //crear ruta de guardado
            $ruta_guardado = 'storage/certificaciones_discapacidad/' . $datos->radicado . '/Autorizacion-certificacion-discapacidad-' . $datos->radicado . '.pdf';

            $detalleCorreo = [
               'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
               'mensaje' => $request->observaciones_solicitud,
               'Subject' => 'Solicitud Aprobada de Autorizacion de la certificacion de discapacidad N°' . $datos->radicado,
               'documento' => 'SI',
               'fecha_pendiente' => $date_30,
               'radicado'  => $datos->radicado,
               'estado' => $request->estado_solicitud,
               'id'=> Crypt::encrypt($request->id)
           ];

            if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones_solicitud = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->adj_certificado = $ruta_guardado;



                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado' => 'Radicado-' . $datos->radicado,
                        'tramite'=>'CERTIFICACION DE DISCAPACIDAD',
                         'radicado'=> $datos->radicado,
                        'accion' => 'update estado ' . $request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud

                    ]);

                    Mail::to($datos->email_responsable)->queue(new NotificacionDiscapacidad($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('salud.discapacidad.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('salud.discapacidad.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('salud.discapacidad.index');
            }
        }elseif($request->estado_solicitud == 'RECHAZADA'){

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
            ]);
            
            $date = date('Y-m-d');
            $date_30 = null;

            $detalleCorreo = [
                'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Rechazada de Autorizacion de la certificacion de discapacidad N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,
                'id'=> Crypt::encrypt($request->id)
            ];
         
              

            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            

                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado' => 'Radicado-' . $datos->radicado,
                        'tramite'=>'CERTIFICACION DE DISCAPACIDAD',
                         'radicado'=> $datos->radicado,
                        'accion' => 'update estado ' . $request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud

                    ]);

                    Mail::to($datos->email_responsable)->send(new NotificacionDiscapacidad($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('salud.discapacidad.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('salud.discapacidad.index');
                }
            
        }
        
   
    }

    //FAMILIA LACTANTE

    public function indexFamilia(){
        $Parametros1 = Parametro::where('ParNomGru', 'LETRA')->get();
        $Parametros2 = Parametro::where('ParNomGru','ABREDIR')->get();
        $Barrios = Barrio::all();
      
         return view('tramites.gestantes.index', compact('Parametros1', 'Parametros2', 'Barrios'));
    }

    public function storeFamilia(Request $request){
        $this->validate($request, [
        "nit"=>"required|unique:familia_lactante",
        "razon_social"=>"required",
        "direccion"=>"required",
        "barrio"=>"required",
        "telefono_empresa"=>"required|digits_between:7,10",
        "correo_electronico"=>"required",
        "nom_representante"=>"required",
        "ape_representante"=>"required",
        "numero_mujeres_empresa"=>"required|digits_between:1,4",
        "numero_mujeres_gestantes"=>"required|digits_between:1,4",
        "numero_mujeres_lactantes"=>"required|digits_between:1,4",
        "tratamiento_datos"=>"required",
        "acepto_terminos"=>"required",
        "confirmo_mayorEdad"=>"required",
        "compartir_informacion"=>"required"
            
        ]);

        $reporte = $request->all();
        $saveSolicitud = FamiliaLactante::create($reporte);

        if ($saveSolicitud) {

            $request->session()->flash('radicado_id', $saveSolicitud->id);
            return redirect()->route('familia.confirmacion');
        } else {
            Alert::error('Ha Ocurrido un error', 'Ha ocurrido un error en en registrar su solicitud');
            return redirect()->route('home');
        }
    }

    public function confirmacion()
    {
        return view('tramites.gestantes.confirmacion');
    }

    public function end()
    {
        Session::flush();
        return redirect("/familia-lactante");
    }
}
    
