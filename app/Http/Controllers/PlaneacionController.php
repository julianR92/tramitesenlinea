<?php

namespace App\Http\Controllers;

use App\EspacioPublico;
use App\Auditoria;
use App\Parqueadero;
use App\AuditoriaParqueadero;
use App\Barrio;
use App\Pot;
use App\Vereda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnvioNotificacion;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PDF;



class PlaneacionController extends Controller
{
    public function __construct()
    {  
    $this->middleware('auth' ,['except' => ['indexPot', 'barriosComunas', 'veredaCorregimiento', 'validacionDocumento', 'potStore', 'confirmacionPot']]);
    }

    public function index()
    {

        return view('tramites.planeacion.index');
    }

    public function espacioIndex()
    {

        $sEnviadas = EspacioPublico::where('estado_solicitud', 'ENVIADA')->get();
        $sProgreso = EspacioPublico::where('estado_solicitud', 'EN PROGRESO')->get();
        $sPendientes = EspacioPublico::where('estado_solicitud', 'PENDIENTE')->get();
        $sEstudio = EspacioPublico::where('estado_solicitud', 'EN ESTUDIO')->get();
        $sAprobadas = EspacioPublico::where('estado_solicitud', 'APROBADA')->get();
        $sRechazadas = EspacioPublico::where('estado_solicitud', 'RECHAZADA')->get();
        $porCerrar =  EspacioPublico::where('estado_solicitud', 'PENDIENTE')->where('fecha_pendiente' ,'<',DB::raw('DATE_ADD(NOW(),INTERVAL 5 DAY)'))->get()->count();        
        $count_enviadas = $sEnviadas->count();
        $count_progreso = $sProgreso->count();
        $count_pendientes = $sPendientes->count();
        $count_aprobadas = $sAprobadas->count();
        $count_rechazadas = $sRechazadas->count();
        $count_enEstudio = $sEstudio->count();

        return view('tramites.planeacion.espacio.index', compact('sEnviadas', 'sProgreso', 'sPendientes', 'sAprobadas', 'sRechazadas', 'count_enviadas', 'count_progreso', 'count_pendientes', 'count_aprobadas', 'count_rechazadas', 'porCerrar', 'sEstudio' , 'count_enEstudio'));
    }

    public function detalleSolicitud($id)
    {

        $solicitud = EspacioPublico::findOrFail($id);

        return view('tramites.planeacion.espacio.detalle', compact('solicitud'));
    }

    public function updateSolicitud(Request $request)
    {

        $datos = EspacioPublico::findOrFail($request->id);

        if ($request->estado_solicitud == 'PENDIENTE') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = date("Y-m-d", strtotime($date . "+ 30 days"));


            $detalleCorreo = [
                'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Documentos Pendientes Solicitud de Intervención de Espacio Publico N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,
                'id'=> Crypt::encrypt($request->id)
            ];

            // actualizar

            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            $datos->fecha_pendiente = $date_30;
            $datos->act_documentos = null;

            if ($datos->save()) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                    'tramite' =>'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
                    'radicado' => $datos->radicado,
                    'accion'=>'update a estado '.$request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud

                ]);

                Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('espacio.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualizacion de la solicitud');
                return redirect()->route('espacio.index');
            }
        } elseif ($request->estado_solicitud == 'EN PROGRESO') {

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
                'Subject' => 'Cita para solicitud de Intervención de Espacio Publico N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];
            // actualizar

            if($request->documento_visita || $request->documento_visita != null){                
            $adjunto10 = $request->file('documento_visita')->storeAs('Respuestas/' . $datos->radicado, 'Concepto-visita-tecnica-' . $datos->radicado . '.pdf');
            //crear ruta de guardado
            $ruta_guardado_visita = 'storage/Respuestas/' . $datos->radicado . '/Concepto-visita-tecnica-' . $datos->radicado . '.pdf';

            $datos->documento_visita =  $ruta_guardado_visita;   


            }

            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            $datos->fecha_pendiente = $date_30;
            $datos->act_documentos = null;

            if ($datos->save()) {

                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                    'tramite' =>'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
                    'radicado' => $datos->radicado,
                    'accion'=>'update a estado '.$request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud
                    

                ]);

                Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('espacio.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualizacion de la solicitud');
                return redirect()->route('espacio.index');
            }
        }elseif ($request->estado_solicitud == 'EN ESTUDIO') {

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
                'Subject' => 'Solicitud en Estudio de Intervención de Espacio Publico N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];
            // actualizar

            if($request->documento_visita || $request->documento_visita != null){                
                $adjunto10 = $request->file('documento_visita')->storeAs('Respuestas/' . $datos->radicado, 'Concepto-visita-tecnica-' . $datos->radicado . '.pdf');
                //crear ruta de guardado
                $ruta_guardado_visita = 'storage/Respuestas/' . $datos->radicado . '/Concepto-visita-tecnica-' . $datos->radicado . '.pdf';
    
                $datos->documento_visita =  $ruta_guardado_visita;   
    
    
                }

            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            $datos->fecha_pendiente = $date_30;
            $datos->act_documentos = null;

            if ($datos->save()) {

                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                    'tramite' =>'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
                    'radicado' => $datos->radicado,
                    'accion'=>'update a estado '.$request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud
                    

                ]);

                Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('espacio.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualizacion de la solicitud');
                return redirect()->route('espacio.index');
            }
        } elseif ($request->estado_solicitud == 'APROBADA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
                "documento_respuesta" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = NULL;


            $detalleCorreo = [
                'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Aprobada de Licencia Intervención de Espacio Publico N°' . $datos->radicado,
                'documento' => 'SI',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];

            //mover documento a storage
            $adjunto1 = $request->file('documento_respuesta')->storeAs('Respuestas/' . $datos->radicado, 'Respuesta_Solicitud-' . $datos->radicado . '.pdf');

            //crear ruta de guardado
            $ruta_guardado = 'storage/Respuestas/' . $datos->radicado . '/Respuesta_Solicitud-' . $datos->radicado . '.pdf';

            if ($adjunto1) {
                // actualizar

                if($request->documento_visita || $request->documento_visita != null){                
                    $adjunto10 = $request->file('documento_visita')->storeAs('Respuestas/' . $datos->radicado, 'Concepto-visita-tecnica-' . $datos->radicado . '.pdf');
                    //crear ruta de guardado
                    $ruta_guardado_visita = 'storage/Respuestas/' . $datos->radicado . '/Concepto-visita-tecnica-' . $datos->radicado . '.pdf';
        
                    $datos->documento_visita =  $ruta_guardado_visita;   
        
        
                    }

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones_solicitud = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->fecha_pendiente = $date_30;
                $datos->documento_respuesta = $ruta_guardado;
                $datos->act_documentos = null;
                

                if ($datos->save()) {
                    //auditoria
                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                        'tramite' =>'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
                        'radicado' => $datos->radicado,
                        'accion'=>'update a estado '.$request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud
    
                    ]);

                    Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('espacio.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('espacio.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('espacio.index');
            }
        }elseif($request->estado_solicitud == 'RECHAZADA'){

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
                "documento_respuesta" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = NULL;


            $detalleCorreo = [
                'nombres' => $datos->nom_responsable . ' ' . $datos->ape_responsable,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Rechazada de Licencia Intervención de Espacio Publico N°' . $datos->radicado,
                'documento' => 'SI',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];

            //mover documento a storage
            $adjunto1 = $request->file('documento_respuesta')->storeAs('Respuestas/' . $datos->radicado, 'Respuesta_Solicitud-' . $datos->radicado . '.pdf');

            //crear ruta de guardado
            $ruta_guardado = 'storage/Respuestas/' . $datos->radicado . '/Respuesta_Solicitud-' . $datos->radicado . '.pdf';

            if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones_solicitud = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->fecha_pendiente = $date_30;
                $datos->documento_respuesta = $ruta_guardado;
                $datos->act_documentos = null;

                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                        'tramite' =>'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
                        'radicado' => $datos->radicado,
                        'accion'=>'update a estado '.$request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud
    
                    ]);

                    Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('espacio.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('espacio.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('espacio.index');
            }

        }
    }

    public function documentSolicitud($id){

        $solicitud= EspacioPublico::findOrFail($id);
        // $pdf = App::make('dompdf.wrapper');
        $pdf = PDF::loadView('tramites.planeacion.espacio.document', compact('solicitud'));
        return $pdf->stream();

    }
    public function indexParqueaderos(){
     
        $sEnRevision = Parqueadero::where('estado_solicitud', 'REVISION-PLANEACION')->get();
        $contador_revision = $sEnRevision->count();
        $sRevisadas = AuditoriaParqueadero::where('estado_solicitud', 'RESPUESTA-PLANEACION')->get();
        $contador_revisadas = $sRevisadas->count();
        $porCerrar =  Parqueadero::where('estado_solicitud', 'REVISION-PLANEACION')->where('fecha_pendiente_planeacion' ,'<',DB::raw('DATE_ADD(NOW(),INTERVAL 5 DAY)'))->get()->count();   

        
        return view('tramites.planeacion.parqueaderos.index', compact('sEnRevision','contador_revision','porCerrar', 'sRevisadas','contador_revisadas'));

    }

    public function parqueaderoDetalle($id){    
    
        $solicitud = Parqueadero::findOrFail($id);

        return view('tramites.planeacion.parqueaderos.detalle', compact('solicitud'));

    }
    public function parqueaderoDetalleAuditoria($id){ 
        
        $solicitud = AuditoriaParqueadero::findOrFail($id);
        return view('tramites.planeacion.parqueaderos.auditoria', compact('solicitud'));

    }
    
    //POT
    public function indexPot(){
        
        $conteo = Pot::all()->count();
        $Barrios = Barrio::all();
        $veredas = Vereda::all();
        return view('tramites.planeacion.pot.index' , compact('Barrios', 'conteo', 'veredas'));
    }

    public function barriosComunas(Request $request){   
           
           $dataBarrio = Barrio::where('codigo', $request->codigo)->first();
            $comuna = DB::table('comuna')->where('codigo', $dataBarrio->codigo_comuna)->first();
            $resp = ["success" => true ,"respuesta" => $comuna];
            return response()->json($resp);   
           
   }

   public function veredaCorregimiento(Request $request){
    $dataVereda = Vereda::where('codigo', $request->codigo)->first();
    $corregimiento = DB::table('corregimiento')->where('codigo', $dataVereda->codigo_corregimiento)->first();
    $resp = ["success" => true ,"respuesta" => $corregimiento];
    return response()->json($resp);



   }

   public function validacionDocumento(Request $request){
    
    
    $dataCount = Pot::where('documento_usuario', $request->codigo)->count();   
    $resp = ["success" => true ,"respuesta" => $dataCount];
    return response()->json($resp);


   }

   public function potStore(Request $request){

      
    $this->validate($request, [
        "documento_usuario" => "required|unique:opinion_pot",
        "nombre_usuario" => "required",
        "apellido_usuario" => "required",        
        "edad" => "required",
        "correo_electronico" => "required",
        "tema" => "required",
        "observacion" => "required|max:300",       
        "tratamiento_datos"=> "required",
        "acepto_terminos"=> "required",
        "confirmo_mayorEdad"=> "required",
        "compartir_informacion"=> "required"         
              
    ]);

    $solicitud = $request->all();
    // return $solicitud;
    $saveSolicitud = Pot::create($solicitud);
    $request->session()->flash('radicado_id', $request->documento_usuario);    
    return redirect()->route('pot.confirmacion');

   }

   public function confirmacionPot()    
    {
        return view('tramites.planeacion.pot.confirmacion');

    }


   

}
