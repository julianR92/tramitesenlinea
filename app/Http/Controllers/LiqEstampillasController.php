<?php

namespace App\Http\Controllers;
use App\Parametro;
use App\Barrio;
use App\LOEstampi;
use App\Auditoria;
use App\Mail\NotificacionEstampillas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class LiqEstampillasController extends Controller
{ 
    public function index(){

       
        return view('tramites.liqestampillas.index');
  
      } 

    public function registro(){

        $Parametros1 = Parametro::where('ParNomGru', 'LETRA')->get();
        $Parametros2 = Parametro::where('ParNomGru','ABREDIR')->get();
        $Barrios = Barrio::all();
      
         return view('tramites.liqestampillas.registro', compact('Parametros1', 'Parametros2', 'Barrios'));
    }

    public function confirmacion()
    {
        return view('tramites.liqestampillas.confirmacion');
    }

    public function end()
    {
        Session::flush();
        return redirect("/liquidacion-estampillas");
    }

    

    public function store(Request $request)
    { 
        
    
      
          
      // validacion campos requeridos
        //   dd($request->all());

          $validator = Validator::make($request->all(),[
              'nom_solicitante'=>'required',
              'ape_solicitante'=>'required',
              'LoTipDoc'=>'required',
              'LoNumIde'=>'required',
              'LoTel'=>'required',
              'LoEmail'=>'required',
              'LoNumActAdm'=>'required',             
              'LoFecAct'=>'required',              
              'LoEnti'=>'required',
              'LoCargo'=>'required',
              'LoCodigo'=>'required',
              'LoGrado'=>'required',
              'LoTipNom'=>'required',
              'LoValMen'=>'required',
              'archivo_acta_posesion'=>'required',
              'archivo_id'=>'required',
              'tratamiento_datos'=>'required',
              'confirmo_mayorEdad'=>'required',
              'acepto_terminos'=>'required',
              'compartir_informacion'=>'required', 
  
           ]);

           $razon_social = $request->nom_solicitante.' '.$request->ape_solicitante;

      

      if($validator->fails()){
          //devuelve errores a la vista
       return response()->json(['error'=>$validator->errors()->all()]);
      }else{
      
      


      $ultimo_id = LOEstampi::latest('LoNroLiq')->first();
      // return $ultimo_id;
      if (!$ultimo_id) {
          $idRadicado = 1;
      } else {
          $idRadicado = $ultimo_id->LoNroLiq + 1;
      }

      

      $radicado = date("Ymd") . $idRadicado; // numero radicado

      $adjunto1 = $request->file('archivo_acta_posesion')->storeAs('documentos_LiqEstampillas/' . $radicado, 'Acta-Posesion-' . $radicado . '.pdf');
       
      $adjunto2 = $request->file('archivo_id')->storeAs('documentos_LiqEstampillas/' . $radicado, 'Doc-identificacion-' . $radicado . '.pdf');
      

      if($request->arch_otros == 'null') {    
          $adjunto6 = false;              
      }else{            
      $adjunto6 = $request->file('arch_otros')->storeAs('documentos_LiqEstampillas/' . $radicado, 'OTROS-ADJUNTOS-' . $radicado . '.pdf');
     
     
      }

       
      if ($adjunto1 && $adjunto2  || $adjunto6) {

           //rutas de guardado
      $AdjActPos = 'storage/documentos_LiqEstampillas/' . $radicado . '/Acta-Posesion-' . $radicado . '.pdf';      
      $AdjNumIde= 'storage/documentos_LiqEstampillas/' . $radicado . '/Doc-identificacion-' . $radicado . '.pdf';

      if($adjunto6){
      $AdjCertSal = 'storage/documentos_LiqEstampillas/' . $radicado . '/OTROS-ADJUNTOS-' . $radicado . '.pdf';
      }else{
      $AdjCertSal = null;
      }

      $request->request->add([

          'LoNombre' => $razon_social,
          'LoRadicado' => $radicado,
          'AdjActPos' => $AdjActPos,            
          'AdjNumIde' => $AdjNumIde,
          'AdjCertSal'=> $AdjCertSal,
          'LoEstSol' => 'ENVIADA',   
          'LoEst' => '0'         
      ]);

      $detalleCorreo_fun = [
        'nombres' => ' Funcionario de Secretaría de Hacienda',
        'radicado' => $radicado,
        'Subject' => 'Solicitud pendiente de Liquidacion oficial de estampillas con Radicado No'.$radicado,
        'documento'=> 'NO',
        'fecha_pendiente' => null,            
        'estado' => 'FUNCIONARIO',
        'mensaje'=> 'Tiene una solicitud de Liquidacion oficial de estampillas radicada en la plataforma pendiente por revisar'
    ];
      $correo_funcionario = 'yavera@bucaramanga.gov.co';  
      $solicitud = $request->all();
         //return $solicitud;
        $saveSolicitud = LOEstampi::create($solicitud);
        $liquidacion_id = $saveSolicitud->LoNroLiq;

      
        $detalleCorreo = [
            'nombres' => $razon_social,
            'radicado' => $radicado,
            'Subject' => 'Envió de Solicitud de Liquidacion oficial de estampillas',
            'documento'=> 'NO',
            'fecha_pendiente' => null,            
            'estado' => null,
            'mensaje'=> null,
            'id'=> Crypt::encrypt($liquidacion_id)
        ];
      

      if($saveSolicitud){

            //auditoria
        $auditoria = Auditoria::create([
            'usuario' => $request->LoNumIde,
            'proceso_afectado'=> 'Radicado-'.$radicado,
            'tramite'=> 'Liquidacion de estampillas',
            'radicado'=> $radicado,
            'accion'=>'SOLICITUD ENVIADA',
            'observacion'=> 'El ciudadano '.$razon_social. ' realiza una solicitud de liquidacion oficial de estampillas'

        ]);


        
        Mail::to($request->LoEmail)->queue(new NotificacionEstampillas($detalleCorreo));
        Mail::to($correo_funcionario)->queue(new NotificacionEstampillas($detalleCorreo_fun));
        $request->session()->flash('radicado_id', $radicado);
        return response()->json(['success'=>'Validacacion paso']);


    }else{
        return response()->json(['validaciones'=>'Error al guardar la solicitud']);

    }
      

      }else{
          return response()->json(['validaciones'=>'Error al cargar los documentos en el sistema']);
      }

          // return response()->json(['success'=>'Validacacion paso']);

      } 
   
    }


    public function updateDocs(Request $request){

        
      $solicitud = LOEstampi::FindOrFail($request->LoNroLiq);
      $contador = 0;
      if($request->archivo_acta_posesion){
          $adjunto1 = $request->file('archivo_acta_posesion')->storeAs('documentos_LiqEstampillas/' . $solicitud->radicado, 'Acta-Posesion-' . $solicitud->radicado . '.pdf');
          $contador++;
      }else{
          $adjunto1 = false;
      }

      if($request->archivo_id){
          $adjunto2 = $request->file('archivo_id')->storeAs('documentos_LiqEstampillas/' . $solicitud->radicado, 'Doc-identificacion-' . $solicitud->radicado . '.pdf');
          $contador++;
      }else{
          $adjunto2 = false;
      }


      if($request->arch_otros){
          $adjunto6 = $request->file('arch_otros')->storeAs('documentos_LiqEstampillas/' . $solicitud->radicado, 'OTROS-ADJUNTOS-' . $solicitud->radicado . '.pdf');
          $solicitud->adj_otros = 'storage/documentos_LiqEstampillas/' . $solicitud->radicado . '/OTROS-ADJUNTOS-' . $solicitud->radicado . '.pdf';
          $contador++;

      }else{
          $adjunto6 = false;
      }


      
      $detalleCorreo = [
        'nombres' => $solicitud->LoNombre,
        'mensaje' => 'Usted ha actualizado los documentos correctamente en el sistema, ahora su solicitud sera revisada nuevamente',
        'Subject' => 'Documentos actualizados en solicitud de espectaculos publicos N°' . $solicitud->radicado,
        'documento'=> 'NO',
        'fecha_pendiente' => null,
        'radicado'  => $solicitud->radicado,
        'estado' => 'SOLICITUD EN ESTUDIO',
        'id'=> Crypt::encrypt($request->id)               

    ];

    $detalleCorreo_fun = [
        'nombres' => 'Funcionario de Secretaría de Hacienda',
        'radicado' => $solicitud->radicado,
        'Subject' => 'Actualización de documentos en solicitud No'.$solicitud->radicado,
        'documento'=> 'NO',
        'fecha_pendiente' => null,            
        'estado' => 'FUNCIONARIO',
        'mensaje'=> 'Tiene la solicitud No '. $solicitud->radicado.' pendiente por revisar en la plataforma debido a la actualización de documentos'
    ];
     $correo_funcionario = 'yavera@bucaramanga.gov.co';
    //$correo_funcionario = 'lcerquera@bucaramanga.gov.co';
    
    if($adjunto1 || $adjunto2 || $adjunto3 || $adjunto4 || $adjunto5 || $adjunto6){

         //auditoria
         $auditoria = Auditoria::create([
            'usuario' => $solicitud->numero_identificacion,
            'proceso_afectado'=> 'Radicado-'.$solicitud->radicado,
            'tramite'=> 'Liquidacion Oficial de estampillas',
            'radicado'=> $solicitud->radicado,
            'accion'=>'update de documentos, cambio a estado DOCUMENTOS_ACTUALIZADOS',
            'observacion'=> 'El ciudadan@ '.$solicitud->nombre_o_razon. ' Actualiza documentos dentro de los plazos dispuestos'

        ]);



        $solicitud->estado_documentos = "SI";
        $solicitud->fecha_pendiente = null;
        $solicitud->estado_solicitud = 'DOCUMENTOS_ACTUALIZADOS';
        $solicitud->observaciones = 'Solicitud en estudio, posterior a la actualización de documentos';
        $solicitud->save();
         // envio de correo                
         Mail::to($solicitud->LoEmail)->send(new NotificacionEspectaculos($detalleCorreo)); 
         Mail::to($correo_funcionario)->send(new NotificacionEspectaculos($detalleCorreo_fun));             
        Alert::success('Operacion exitosa', 'Se han cargado '.$contador.' archivo(s) en el sistema');
        return redirect()->route('espectaculos.index');

    }else{
        Alert::error('Error', 'No se ha realizado la carga de los archivos en el sistema');
        return redirect()->route('espectaculos.index');
    }

    
  }



}