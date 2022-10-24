<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auditoria;
use App\Rita;
use App\AnexosRita;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\NotificacionesRITA;
use RealRashid\SweetAlert\Facades\Alert;

class JuridicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tramites.juridica.index');
    }
    public function main()
    {
        return view('tramites.juridica.rita.main');
    }

    public function denuncias()
    {
        $deRadicadas = Rita::where('estado_solicitud', 'RADICADA')->get();
        // $sPendientes = Rita::where('estado_solicitud', 'PENDIENTE')->get();
        $deContestada = Rita::where('estado_solicitud', 'CONTESTADA')->get();
        $deRemitidas = Rita::where('estado_solicitud', 'REMITIDA POR COMPETENCIA')->get();
        $deNoContestada = Rita::where('estado_solicitud', 'NO CONTESTADA')->get();
        // $sRechazadas = Rita::where('estado_solicitud', 'RECHAZADA')->get();
        $countRa = $deRadicadas->count();
        // $count_pendientes = $sPendientes->count();
        $countCon = $deContestada->count();
        $countRemi = $deRemitidas->count();
        $countNoCon = $deNoContestada->count();
        $porCerrar =  Rita::where('fecha_limite' ,'<',DB::raw('DATE_ADD(NOW(),INTERVAL 5 DAY)'))->where('estado_solicitud', 'RADICADA')->get()->count();
        // $count_rechazadas = $sRechazadas->count();
        return view('tramites.juridica.rita.index', compact('deRadicadas','deContestada', 'deRemitidas', 'deNoContestada','countRa', 'countCon', 'countRemi', 'countNoCon','porCerrar' ));
    }

    public function denunciasDetalle($id){

        $solicitud = Rita::where('id',$id)->join('departamento', 'departamento.codigo_depto', '=', 'rita.departamento')->get();
        $trazabilidad = Auditoria::where('radicado',$solicitud[0]->radicado)->where('tramite','LIKE', 'RITA')->get();
        $anexos = AnexosRita::where('rita_id',$id)->get();
        $respuestas = AnexosRita::where('rita_id',$id)->where('titulo', 'Respuestas')->get();
        return view('tramites.juridica.rita.detalle', compact('solicitud','trazabilidad', 'anexos', 'respuestas'));     


    }

    public function updateDenuncia(Request $request){
        $validator = Validator::make($request->all(),[
            'estado_solicitud'=>'required',
            'observaciones_solicitud'=>'required',
            'id'=>'required',
           ]);

           if($validator->fails()){         
            return response()->json(['sucess'=>false,'error'=>$validator->errors()->all()]);
         }else{
           
            $rita = Rita::findOrFail($request->id);
            $rita->estado_solicitud = $request->estado_solicitud;
            $rita->observaciones_solicitud = $request->observaciones_solicitud;
            $rita->fecha_actuacion = date('Y-m-d');
            $files = array();
            if($rita->save()){
                $auditoria = Auditoria::create([
                    'usuario' => auth()->user()->username,
                    'proceso_afectado'=> 'Radicado-'.$rita->radicado,
                    'tramite'=> 'RITA',
                    'radicado'=> $rita->radicado,
                    'accion'=>'DENUNCIA CAMBIA DE ESTADO A : '.$request->estado_solicitud,
                    'observacion'=> $request->observaciones_solicitud
    
                ]);
                if($request->countFiles>0){
                    for ($x = 0; $x < $request->countFiles; $x++) {
                        if ($request->hasFile('files'.$x)){
                            $ext = '.'.$request->file('files'.$x)->getClientOriginalExtension();
                            $anexos = $request->file('files'.$x)->storeAs('documentos_RITA/' . $rita->radicado, 'Respuesta-'.$x.'-' . $rita->radicado . $ext);

                        } 
                        $anexos = New AnexosRita();
                        $anexos->rita_id = $rita->id;
                        $anexos->radicado = $rita->radicado;
                        $anexos->titulo = 'Respuestas';
                        $anexos->nombre_archivo = 'Respuesta-'.$x.'-' . $rita->radicado . $ext;
                        $anexos->ruta= 'storage/documentos_RITA/' . $rita->radicado .'/Respuesta-'.$x.'-' . $rita->radicado . $ext;
                        $anexos->save();
                        $files[] = 'Respuesta-'.$x.'-' . $rita->radicado . $ext;
           
                }                    
            

            }

            
            $detalleCorreo = [
                'mensaje' => $request->observaciones_solicitud,
                'Subject' => 'CONTESTACION DE DENUNCIA '.$rita->radicado. ' RITA',
                'documento' => ($request->countFiles)? 'SI': 'NO',
                'files' => $files,
                'radicado'  => $rita->radicado,
                'estado' => $request->estado_solicitud,

            ];
            if($rita->medio_respuesta=='CORREO ELECTRONICO'){
                if($rita->email_responsable){
                    Mail::to($rita->email_responsable)->send(new NotificacionesRITA($detalleCorreo));
                }
            }

            $resp = ["success" => true,'titulo' => 'Proceso exitoso!', 'message' => 'Denuncia Actualizada','icono'=>'success'];
            return response()->json($resp);

         }
        
    }
  }



}
