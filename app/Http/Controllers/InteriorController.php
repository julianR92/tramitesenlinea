<?php

namespace App\Http\Controllers;

use App\Auditoria;
use App\Parqueadero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\NotificacionParqueaderos;

class InteriorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tramites.interior.index');
    }

    public function parqueaderoIndex()
    {

        $sEnviadas = Parqueadero::where('estado_solicitud', 'ENVIADA')->get();
        $sPendientes = Parqueadero::where('estado_solicitud', 'PENDIENTE')->get();
        $sEnRevision = Parqueadero::where('estado_solicitud', 'REVISION-PLANEACION')->get();
        $sRevisadas = Parqueadero::where('estado_solicitud', 'RESPUESTA-PLANEACION')->get();
        $sAprobadas = Parqueadero::where('estado_solicitud', 'APROBADA')->get();
        $sRechazadas = Parqueadero::where('estado_solicitud', 'RECHAZADA')->get();

        return view('tramites.interior.parqueaderos.index', compact('sEnviadas', 'sEnRevision', 'sPendientes', 'sRevisadas', 'sAprobadas', 'sRechazadas'));
    }

    public function parqueaderoDetalle($id)
    {

        $solicitud = Parqueadero::findOrFail($id);

        return view('tramites.interior.parqueaderos.detalle', compact('solicitud'));
    }

    public function parqueaderoUpdate(Request $request)
    {

        $datos = Parqueadero::findOrFail($request->id);

        if ($request->estado_solicitud == 'PENDIENTE') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'
            ]);


            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = date("Y-m-d", strtotime($date . "+ 15 days"));


            $detalleCorreo = [
                'nombres' => $datos->nom_solicitante . ' ' . $datos->ape_solicitante,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Documentos Pendientes Solicitud de Categorización de Parqueaderos N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
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
                    'accion' => 'update estado ' . $request->estado_solicitud . ' Tramite-Parqueaderos'

                ]);

                Mail::to($datos->email_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('interior.parqueaderos.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
                return redirect()->route('interior.parqueaderos.index');
            }
        } elseif ($request->estado_solicitud == 'REVISION-PLANEACION') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = NULL;
            $date_planeacion = date("Y-m-d", strtotime($date . "+ 30 days"));

            $detalleCorreo = [
                'nombres' => '',
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Revision de Solicitud Pendiente Categorización de parqueaderos N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => 'FUNCIONARIO',

            ];

            // actualizar datos
            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;
            $datos->fecha_pendiente = $date_30;
            $datos->act_documentos = null;
            $datos->fecha_pendiente_planeacion = $date_planeacion;

            $correo_responsable = 'ojrincon@bucaramanga.gov.co';

            if ($datos->save()) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado' => 'Radicado-' . $datos->radicado,
                    'accion' => 'update estado ' . $request->estado_solicitud . ' Tramite-Parqueaderos'

                ]);

                Mail::to($correo_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('interior.parqueaderos.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
            }
        } elseif ($request->estado_solicitud == 'RESPUESTA-PLANEACION') {

            $this->validate($request, [
                "observaciones_planeacion" => 'required',
                "documento_respuesta_planeacion" => 'required',
                "estado_solicitud" => 'required'
            ]);

            $date = date('Y-m-d');
            //sumo 30 días
            $date_30 = NULL;
            $date_planeacion = null;


            //mover documento a storage
            $adjunto1 = $request->file('documento_respuesta_planeacion')->storeAs('public/documentos_parqueaderos/' . $datos->radicado, 'Concepto_Tecnico-' . $datos->radicado . '.pdf');

            //crear ruta de guardado
            $ruta_guardado = 'storage/documentos_parqueaderos/' . $datos->radicado . '/Concepto_Tecnico-' . $datos->radicado . '.pdf';
            $correo_responsable = 'ojrincon@bucaramanga.gov.co';

            $detalleCorreo = [
                'nombres' => '',
                'mensaje' => $request->observaciones_planeacion,
                'Subject' => 'Respuesta concepto Tecnico de Solicitud N°' . $datos->radicado,
                'documento' => 'NO',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => 'FUNCIONARIO',

            ];

            if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones_solicitud = $request->observaciones_planeacion;
                $datos->fecha_actuacion = $date;
                $datos->fecha_pendiente_planeacion = $date_planeacion;
                $datos->adjunto_resPlaneacion = $ruta_guardado;
                $datos->act_documentos = null;



                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado' => 'Radicado-' . $datos->radicado,
                        'accion' => 'update estado ' . $request->estado_solicitud . ' Tramite-Parqueaderos'

                    ]);

                    Mail::to($correo_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('planeacion.parqueaderos.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('planeacion.parqueaderos.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('planeacion.parqueaderos.index');
            }
        } elseif ($request->estado_solicitud == 'RECHAZADA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required'
            ]);

            // fecha de actuacion
            $date = date('Y-m-d');

            $date_30 = null;

            $detalleCorreo = [
                'nombres' => $datos->nom_solicitante . ' ' . $datos->ape_solicitante,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Rechazada N°' . $datos->radicado,
                'documento' => 'RT',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud
            ];

            $datos->estado_solicitud = $request->estado_solicitud;
            $datos->observaciones_solicitud = $request->observaciones_solicitud;
            $datos->fecha_actuacion = $date;


            if ($datos->save()) {

                $auditoria = Auditoria::create([
                    'usuario' => $request->username,
                    'proceso_afectado' => 'Radicado-' . $datos->radicado,
                    'accion' => 'update estado ' . $request->estado_solicitud . ' Tramite-Parqueaderos'

                ]);

                Mail::to($datos->email_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
                Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                return redirect()->route('interior.parqueaderos.index');
            } else {

                Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                return redirect()->route('interior.parqueaderos.index');
            }
        } elseif ($request->estado_solicitud == 'APROBADA') {

            $this->validate($request, [
                "observaciones_solicitud" => 'required',
                "estado_solicitud" => 'required',
                "documento_respuesta" => 'required'
            ]);

            $date = date('Y-m-d');
            $date_30 = null;

            //mover documento a storage
            $adjunto1 = $request->file('documento_respuesta')->storeAs('public/documentos_parqueaderos/' . $datos->radicado, 'Acto_Administrativo_solicitiud_No-' . $datos->radicado . '.pdf');

            //crear ruta de guardado
            $ruta_guardado = 'storage/documentos_parqueaderos/' . $datos->radicado . '/Acto_Administrativo_solicitiud_No-' . $datos->radicado . '.pdf';

            $detalleCorreo = [
                'nombres' => $datos->nom_solicitante . ' ' . $datos->ape_solicitante,
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'Solicitud Aprobada N°' . $datos->radicado,
                'documento' => 'SI',
                'fecha_pendiente' => $date_30,
                'radicado'  => $datos->radicado,
                'estado' => $request->estado_solicitud,

            ];

            if ($adjunto1) {
                // actualizar

                $datos->estado_solicitud = $request->estado_solicitud;
                $datos->observaciones_solicitud = $request->observaciones_solicitud;
                $datos->fecha_actuacion = $date;
                $datos->adjunto_respuesta = $ruta_guardado;



                if ($datos->save()) {

                    $auditoria = Auditoria::create([
                        'usuario' => $request->username,
                        'proceso_afectado' => 'Radicado-' . $datos->radicado,
                        'accion' => 'update estado ' . $request->estado_solicitud . ' Tramite-Parqueaderos'

                    ]);

                    Mail::to($datos->email_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
                    Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
                    return redirect()->route('interior.parqueaderos.index');
                } else {

                    Alert::error('Error', 'Ha ocurrido un error al registrar la actualizacion de la solicitud');
                    return redirect()->route('interior.parqueaderos.index');
                }
            } else {

                Alert::error('Error', 'Ocurrio un error al cargar el archivo al servidor');
                return redirect()->route('interior.parqueaderos.index');
            }
        }
    }
}