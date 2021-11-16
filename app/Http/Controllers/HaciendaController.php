<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\EspectaculosPublicos;
use App\Boletas;
use Illuminate\Support\Facades\Mail;
use App\Auditoria;
use App\Mail\NotificacionEspectaculos;
use Illuminate\Support\Facades\Crypt;


class HaciendaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('tramites.hacienda.index');
    }

    public function espectaculoIndex(){

        
        $sEnviadas = EspectaculosPublicos::where('estado_solicitud', 'ENVIADA')->get();
        $sGarantia = EspectaculosPublicos::where('estado_solicitud', 'ENTREGA_GARANTIA')->get();
        $sPendientes = EspectaculosPublicos::where('estado_solicitud', 'PENDIENTE')->get();
        $sEstudio = EspectaculosPublicos::where('estado_solicitud', 'DOCUMENTOS_ACTUALIZADOS')->get();
        $sAprobadas = EspectaculosPublicos::where('estado_solicitud', 'EVENTO_APROBADO')->get();
        $sRechazadas = EspectaculosPublicos::where('estado_solicitud', 'RECHAZADA')->get();
        $sCanceladas = EspectaculosPublicos::where('estado_solicitud', 'EVENTO_CANCELADO')->get();
        $sActoRevoca = EspectaculosPublicos::where('estado_solicitud', 'ACTO_REVOCADO')->get();
        $porCerrar =  EspectaculosPublicos::where('estado_solicitud', 'PENDIENTE')->where('fecha_pendiente' ,'<',DB::raw('DATEADD(day,5, CONVERT (date, GETDATE()))'))->get()->count();        
        $count_enviadas = $sEnviadas->count();
        $count_garantias = $sGarantia->count();
        $count_pendientes = $sPendientes->count();
        $count_aprobadas = $sAprobadas->count();
        $count_rechazadas = $sRechazadas->count();
        $count_enEstudio = $sEstudio->count();
        $count_canceladas = $sCanceladas->count();
        $count_revoca = $sActoRevoca->count();

        return view('tramites.hacienda.espectaculos.index', compact('sEnviadas', 'sPendientes','sRechazadas', 'count_enviadas', 'count_pendientes', 'count_rechazadas', 'porCerrar','sGarantia', 'count_garantias', 'sEstudio', 'count_enEstudio','sAprobadas', 'count_aprobadas', 'sCanceladas', 'count_canceladas', 'sActoRevoca', 'count_revoca' ));



    }

    public function espectaculoDetalle($id){

        $solicitud = EspectaculosPublicos::findOrFail($id);
        $boletas = Boletas::where('espectaculo_id',$id)->get();
    
        return view('tramites.hacienda.espectaculos.detalle', compact('solicitud', 'boletas'));


    }

    public function espectaculoUpdate(Request $request){

        $datos = EspectaculosPublicos::findOrFail($request->id);
        if ($request->estado_solicitud == 'ENTREGA_GARANTIA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
                "tipo_garantia"=> 'required',
                "numero_poliza_cheque"=> 'required',
                "valor_poliza_cheque"=> 'required',
                "arch_documentoGarantia"=> 'required',
                "arch_acta_reunion"=> 'required'
            ]);
          
            
            $date = date('Y-m-d');
            $date_30 = null;

            //mover documento a storage
            $adjunto1 = $request->file('arch_documentoGarantia')->storeAs('documentos_espectaculos/' . $datos->radicado, 'GARANTIA-' . $datos->radicado . '.pdf');
            $adjunto2 = $request->file('arch_acta_reunion')->storeAs('documentos_espectaculos/' . $datos->radicado, 'ACTA-REUNION-ENTREGA-' . $datos->radicado . '.pdf');
            //crear ruta de guardado
            $ruta_garantia = 'storage/documentos_espectaculos/' . $datos->radicado . '/GARANTIA-' . $datos->radicado . '.pdf';
            $ruta_acta = 'storage/documentos_espectaculos/' . $datos->radicado . '/ACTA-REUNION-ENTREGA-' . $datos->radicado . '.pdf';
            
            $detalleCorreo = [
                'nombres' => $datos->nombre_o_razon,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Recepción de garantía física de solicitud de espectaculos publicos N°' . $datos->radicado,
                'documento'=> 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,
                

            ];
            if($adjunto1 && $adjunto2){

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->tipo_garantia = $request->tipo_garantia;
                $datos->numero_poliza_cheque = $request->numero_poliza_cheque;
                $datos->valor_poliza_cheque = $request->valor_poliza_cheque;
                $datos->adj_documentoGarantia = $ruta_garantia;
                $datos->adj_acta_reunion = $ruta_acta;

                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado' => 'Radicado-' . $datos->radicado,
                        'tramite'=>'ESPECTACULOS PUBLICOS',
                        'radicado'=> $datos->radicado,
                        'accion' => 'actualización de estado del tramite a ' . $request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud

                    ]);

                    Mail::to($datos->email_responsable)->queue(new NotificacionEspectaculos($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('hacienda.espectaculos.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('hacienda.espectaculos.index');
                }




            }else{
                Alert::error('Error', 'Ocurrio un error al cargar los archivos al servidor');
                return redirect()->route('hacienda.espectaculos.index');
            }



        }else if($request->estado_solicitud == 'PENDIENTE'){
          
            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'               
            ]);

            $date = date('Y-m-d');
            $date_3 = date("Y-m-d", strtotime($datos->fecha_inicio_evento . "-3 day"));            
           

            $detalleCorreo = [
                'nombres' => $datos->nombre_o_razon,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Documentos pendientes en solicitud de espectaculos publicos N°' . $datos->radicado,
                'documento'=> 'NO',
                'fecha_pendiente' => $date_3,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,
                'id'=> Crypt::encrypt($request->id)               

            ];

             // actualizar datos
             $datos->estado_solicitud = $request->estado_solicitud;
             $datos->observaciones = $request->observaciones_solicitud;
             $datos->fecha_actuacion = $date;
             $datos->fecha_pendiente = $date_3;

             if ($datos->save()) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado' => 'Radicado-' . $datos->radicado,
                    'tramite'=>'ESPECTACULOS PUBLICOS',
                    'radicado'=> $datos->radicado,
                    'accion' => 'actualización de estado del tramite a ' . $request->estado_solicitud,
                    'observacion'=>$request->observaciones_solicitud

                ]);


                Mail::to($datos->email_responsable)->queue(new NotificacionEspectaculos($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('hacienda.espectaculos.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
                return redirect()->route('hacienda.espectaculos.index');
            }
             


        }else if($request->estado_solicitud == 'EVENTO_APROBADO'){

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required' ,
                "arch_acto_administrativo"=>'required'              
            ]);

            $date = date('Y-m-d');

            $detalleCorreo = [
                'nombres' => $datos->nombre_o_razon,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Aprobada de Espectáculo Publico de Radicado N°' . $datos->radicado,
                'documento' => 'SI',
                'fecha_pendiente' => null,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];

              //mover documento a storage
              $adjunto1 = $request->file('arch_acto_administrativo')->storeAs('documentos_espectaculos/' . $datos->radicado, 'ACTO-ADMINISTRATIVO-RAD-' . $datos->radicado . '.pdf');

              //crear ruta de guardado
              $ruta_guardado = 'storage/documentos_espectaculos/' . $datos->radicado . '/ACTO-ADMINISTRATIVO-RAD-' . $datos->radicado . '.pdf';
           
              if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->fecha_pendiente = null;
                $datos->adj_acto_administrativo = $ruta_guardado;
                $datos->estado_documentos = null;
                

                if ($datos->save()) {
                    //auditoria
                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                        'tramite' =>'ESPECTACULOS PUBLICOS',
                        'radicado' => $datos->radicado,
                        'accion'=>'update a estado '.$request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud
    
                    ]);

                    Mail::to($datos->email_responsable)->send(new NotificacionEspectaculos($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('hacienda.espectaculos.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('hacienda.espectaculos.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('hacienda.espectaculos.index');
            }


        }else if($request->estado_solicitud == 'ACTO_REVOCADO'){

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required' ,
                "arch_acto_revocatorio"=>'required'              
            ]);

            $date = date('Y-m-d');

            $detalleCorreo = [
                'nombres' => $datos->nombre_o_razon,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud de acto revocatorio de Espectáculo Publico de Radicado N°' . $datos->radicado,
                'documento' => 'SI-REVOCA',
                'fecha_pendiente' => null,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];

              //mover documento a storage
              $adjunto1 = $request->file('arch_acto_revocatorio')->storeAs('documentos_espectaculos/' . $datos->radicado, 'ACTO-ADMINISTRATIVO-REVOCADO-RAD-' . $datos->radicado . '.pdf');

              //crear ruta de guardado
              $ruta_guardado = 'storage/documentos_espectaculos/' . $datos->radicado . '/ACTO-ADMINISTRATIVO-REVOCADO-RAD-' . $datos->radicado . '.pdf';
           
              if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->fecha_pendiente = null;
                $datos->adj_acto_revocatorio = $ruta_guardado;
                $datos->estado_documentos = null;
                

                if ($datos->save()) {
                    //auditoria
                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado'=> 'Radicado-'.$datos->radicado,
                        'tramite' =>'ESPECTACULOS PUBLICOS',
                        'radicado' => $datos->radicado,
                        'accion'=>'update a estado '.$request->estado_solicitud,
                        'observacion'=>$request->observaciones_solicitud
    
                    ]);

                    Mail::to($datos->email_responsable)->send(new NotificacionEspectaculos($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('hacienda.espectaculos.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('hacienda.espectaculos.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('hacienda.espectaculos.index');
            }





        }
        // return $request;
    }
}
