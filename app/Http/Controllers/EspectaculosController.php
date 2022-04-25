<?php

namespace App\Http\Controllers;
use App\Parametro;
use App\Barrio;
use App\EspectaculosPublicos;
use App\Boletas;
use App\Auditoria;
use App\NitEspectaculos;
use App\Mail\NotificacionEspectaculos;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class EspectaculosController extends Controller
{
    
    public function index(){

        $Parametros1 = Parametro::where('ParNomGru', 'LETRA')->get();
        $Parametros2 = Parametro::where('ParNomGru','ABREDIR')->get();
        $Barrios = Barrio::all();

     return view('tramites.espectaculos.index',compact('Parametros1', 'Parametros2', 'Barrios'));

    } 

    public function confirmacion()
    {
        return view('tramites.espectaculos.confirmacion');
    }

    public function end()
    {
        Session::flush();
        return redirect("/espectaculos-publicos");
    }

    public function detalle($id){

        $solicitud = EspectaculosPublicos::FindOrFail(Crypt::decrypt($id));

        $date = Carbon::now();
        $date1 = Carbon::parse($solicitud->fecha_pendiente);
        $diff = $date1->diffInDays($date);      
        
        return view('tramites.espectaculos.detalle', compact('solicitud', 'diff'));



    }

    public function consulta(Request $request){

        $QuerySolicitud = EspectaculosPublicos::where($request->tipo_parametro, $request->parametro)->get();

        if ($QuerySolicitud->count() > 0) {
            
            // return $QuerySolicitud;
            return view('tramites.espectaculos.resultado', compact('QuerySolicitud'));
        } else {
            Alert::error('Ha Ocurrido un error', 'El Numero: .' . $request->parametro . ' no tiene registros asociados');
            return redirect()->route('espectaculos.index');
        }
    }

    public function store(Request $request){  
        
        $boletas = json_decode($request->boletas);

        
        
    
        
        // validacion campos requeridos
        if($request->tipo_persona == 'J'){
         $validator = Validator::make($request->all(),[
            'tipo_persona'=>'required',
            'razon_social'=>'required',
            'tipo_identificacion'=>'required',
            'numero_identificacion'=>'required',
            'direccion_notificacion'=>'required',
            'barrio_notificacion'=>'required',
            'telefono_movil'=>'required',
            'email_responsable'=>'required',
            'nombre_evento'=>'required',
            'id_evento'=>'required',
            'fecha_inicio_evento'=>'required',
            'hora_inicio'=>'required',
            'hora_fin'=>'required',
            'direccion_evento'=>'required',
            'barrio_evento'=>'required',
            'descripcion_evento'=>'required',
            'tipo_garantia'=>'required',
            'archivo_rut'=>'required',
            'archivo_camara'=>'required',
            'archivo_boleteria'=>'required',
            'archivo_copia_cedula'=>'required',
            'tratamiento_datos'=>'required',
            'confirmo_mayorEdad'=>'required',
            'acepto_terminos'=>'required',
            'compartir_informacion'=>'required', 

         ]);

         $razon_social = $request->razon_social;

        }else{

            $validator = Validator::make($request->all(),[
                'tipo_persona'=>'required',
                'nom_solicitante'=>'required',
                'ape_solicitante'=>'required',
                'tipo_identificacion'=>'required',
                'numero_identificacion'=>'required',
                'direccion_notificacion'=>'required',
                'barrio_notificacion'=>'required',
                'telefono_movil'=>'required',
                'email_responsable'=>'required',
                'nombre_evento'=>'required',
                'id_evento'=>'required',
                'fecha_inicio_evento'=>'required',
                'hora_inicio'=>'required',
                'hora_fin'=>'required',
                'direccion_evento'=>'required',
                'barrio_evento'=>'required',
                'descripcion_evento'=>'required',
                'tipo_garantia'=>'required',
                'archivo_rut'=>'required',
                'archivo_camara'=>'required',
                'archivo_boleteria'=>'required',
                'archivo_copia_cedula'=>'required',
                'tratamiento_datos'=>'required',
                'confirmo_mayorEdad'=>'required',
                'acepto_terminos'=>'required',
                'compartir_informacion'=>'required', 
    
             ]);

             $razon_social = $request->nom_solicitante.' '.$request->ape_solicitante;

        }

        if($validator->fails()){
            //devuelve errores a la vista
         return response()->json(['error'=>$validator->errors()->all()]);
        }else{
        
        if(!$request->fecha_fin || $request->fecha_fin == null){
            $fecha_fin_evento = $request->fecha_inicio_evento;
        }else{
            $fecha_fin_evento = $request->fecha_fin;
        }


        $ultimo_id = EspectaculosPublicos::latest('id')->first();
        // return $ultimo_id;
        if (!$ultimo_id) {
            $idRadicado = 1;
        } else {
            $idRadicado = $ultimo_id->id + 1;
        }

        

        $radicado = date("Ymd") . $idRadicado; // numero radicado

        $adjunto1 = $request->file('archivo_rut')->storeAs('documentos_espectaculos/' . $radicado, 'RUT-' . $radicado . '.pdf');
         
        $adjunto2 = $request->file('archivo_camara')->storeAs('documentos_espectaculos/' . $radicado, 'CAMARA-COMERCIO-' . $radicado . '.pdf');
         
        $adjunto3 = $request->file('archivo_boleteria')->storeAs('documentos_espectaculos/' . $radicado, 'CERTIFICADO-BOLETERIA-' . $radicado . '.pdf');

        $adjunto4 = $request->file('archivo_copia_cedula')->storeAs('documentos_espectaculos/' . $radicado, 'DOCUMENTO-IDENTIDAD-' . $radicado . '.pdf');

        $adjunto5 = $request->file('archivo_solicitud')->storeAs('documentos_espectaculos/' . $radicado, 'SOLICITUD-' . $radicado . '.pdf');
         
        if ($adjunto1 && $adjunto2 && $adjunto3 && $adjunto4 && $adjunto5) {

             //rutas de guardado
        $adj_RUT = 'storage/documentos_espectaculos/' . $radicado . '/RUT-' . $radicado . '.pdf';      
        $adj_camara_comercio= 'storage/documentos_espectaculos/' . $radicado . '/CAMARA-COMERCIO-' . $radicado . '.pdf';
        $adj_certificacion_boleteria_emitida = 'storage/documentos_espectaculos/' . $radicado . '/CERTIFICADO-BOLETERIA-' . $radicado . '.pdf';      
        $adj_documentoident= 'storage/documentos_espectaculos/' . $radicado . '/DOCUMENTO-IDENTIDAD-' . $radicado . '.pdf';
        $adj_solicitud= 'storage/documentos_espectaculos/' . $radicado . '/SOLICITUD-' . $radicado . '.pdf';

        $request->request->add([

            'nombre_o_razon' => $razon_social,
            'radicado' => $radicado,
            'evento_id'=> $request->id_evento,
            'fecha_fin_evento'=> $fecha_fin_evento,
            'lugar_evento'=>$request->direccion_evento.', '.$request->barrio_evento,
            'adj_RUT' => $adj_RUT,            
            'adj_camara_comercio' => $adj_camara_comercio,
            'adj_certificacion_boleteria_emitida'=> $adj_certificacion_boleteria_emitida,
            'adj_documentoident' => $adj_documentoident,
            'adj_solicitud'=> $adj_solicitud,
            'estado_solicitud' => 'ENVIADA',            
            'observaciones' => 'SOLICITUD ENVIADA EN ESPERA DE QUE EL SOLICITANTE APORTE DE MANERA FISICA LO MAS PRONTO LA GARANTIA EN LA DIRECCION FASE II: CRA 11 #34-52 PISO 2 - ALCALDIA DE BUCARAMANGA, PARA CONTINUAR CON EL TRAMITE',
            'fecha_actuacion'=> date('Y-m-d')
        ]);

        
        $detalleCorreo_fun = [
            'nombres' => ' Funcionario xxx',
            'radicado' => $radicado,
            'Subject' => 'Solicitud pendiente de Espectaculo publico con Radicado No'.$radicado,
            'documento'=> 'NO',
            'fecha_pendiente' => null,            
            'estado' => 'FUNCIONARIO',
            'mensaje'=> 'Tiene una solicitud pendiente en la plataforma'
        ];
        $correo_funcionario = 'ojrincon@bucaramanga.gov.co';

        $solicitud = $request->all();
        // return $solicitud;
        $saveSolicitud = EspectaculosPublicos::create($solicitud);
        $espectaculo_id = $saveSolicitud->id;

        $detalleCorreo = [
            'nombres' => $razon_social,
            'radicado' => $radicado,
            'Subject' => 'Envió de Solicitud de Espectaculo Publico',
            'documento'=> 'NO',
            'fecha_pendiente' => null,            
            'estado' => null,
            'mensaje'=> null,
            'id'=> Crypt::encrypt($espectaculo_id)
        ];


        if($saveSolicitud){

            foreach ($boletas as $boleta){
                $datos = new Boletas();
                $datos->espectaculo_id = $espectaculo_id;
                $datos->clase_boleta = $boleta->clase_boleta;
                $datos->valor = $boleta->valor;
                $datos->numero_boletas_emitidas = $boleta->numero_boletas_emitidas;                
                $datos->save();
                  
               }

                //auditoria
            $auditoria = Auditoria::create([
                'usuario' => $request->numero_identificacion,
                'proceso_afectado'=> 'Radicado-'.$radicado,
                'tramite'=> 'ESPECTACULOS PUBLICOS',
                'radicado'=> $radicado,
                'accion'=>'SOLICITUD ENVIADA',
                'observacion'=> 'El ciudadano '.$razon_social. ' realiza una solicitud de realización de especatculo publico'

            ]);


            
            Mail::to($request->email_responsable)->queue(new NotificacionEspectaculos($detalleCorreo));
            Mail::to($correo_funcionario)->queue(new NotificacionEspectaculos($detalleCorreo_fun));
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

        
        $solicitud = EspectaculosPublicos::FindOrFail($request->id);
        $contador = 0;
        if($request->archivo_rut){
            $adjunto1 = $request->file('archivo_rut')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'RUT-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto1 = false;
        }

        if($request->archivo_camara){
            $adjunto2 = $request->file('archivo_camara')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'CAMARA-COMERCIO-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto2 = false;
        }

        if($request->archivo_boleteria){
            $adjunto3 = $request->file('archivo_boleteria')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'CERTIFICADO-BOLETERIA-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto3= false;
        }

        if($request->archivo_copia_cedula){
            $adjunto4 = $request->file('archivo_copia_cedula')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'DOCUMENTO-IDENTIDAD-' . $solicitud->radicado . '.pdf');
            $contador++;

        }else{
            $adjunto4 = false;
        }

        if($request->archivo_solicitud){
            $adjunto5 = $request->file('archivo_solicitud')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'SOLICITUD-' . $solicitud->radicado . '.pdf');
            $contador++;

        }else{
            $adjunto5 = false;
        }

        $detalleCorreo = [
            'nombres' => $solicitud->nombre_o_razon,
            'mensaje' => 'Usted ha actualizado los documentos correctamente en el sistema, ahora su solicitud sera revisada nuevamente',
            'Subject' => 'Documentos actualizados en solicitud de espectaculos publicos N°' . $solicitud->radicado,
            'documento'=> 'NO',
            'fecha_pendiente' => null,
            'radicado'  => $solicitud->radicado,
            'estado' => 'SOLICITUD EN ESTUDIO',
            'id'=> Crypt::encrypt($request->id)               

        ];

        $detalleCorreo_fun = [
            'nombres' => ' Funcionario',
            'radicado' => $solicitud->radicado,
            'Subject' => 'Actualización de documentos en solicitud No'.$solicitud->radicado,
            'documento'=> 'NO',
            'fecha_pendiente' => null,            
            'estado' => 'FUNCIONARIO',
            'mensaje'=> 'Tiene la solicitud No '. $solicitud->radicado.' pendiente por revisar en la plataforma debido a la actualización de documentos'
        ];
        $correo_funcionario = 'julianrincon9230@gmail.com';
        
        if($adjunto1 || $adjunto2 || $adjunto3 || $adjunto4 || $adjunto5){

             //auditoria
             $auditoria = Auditoria::create([
                'usuario' => $solicitud->numero_identificacion,
                'proceso_afectado'=> 'Radicado-'.$solicitud->radicado,
                'tramite'=> 'ESPECTACULOS PUBLICOS',
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
             Mail::to($solicitud->email_responsable)->send(new NotificacionEspectaculos($detalleCorreo)); 
             Mail::to($correo_funcionario)->send(new NotificacionEspectaculos($detalleCorreo_fun));             
            Alert::success('Operacion exitosa', 'Se han cargado '.$contador.' archivo(s) en el sistema');
            return redirect()->route('espectaculos.index');

        }else{
            Alert::error('Error', 'No se ha realizado la carga de los archivos en el sistema');
            return redirect()->route('espectaculos.index');
        }



    }

    public function cancelar($id){

        $solicitud = EspectaculosPublicos::FindOrFail(Crypt::decrypt($id));

        $hoy = date("Y-m-d");
        $date_3 = date("Y-m-d", strtotime($solicitud->fecha_inicio_evento . "-3 day"));
        $estado = $solicitud->estado_solicitud;
        $radicado = $solicitud->radicado;
        $id = $solicitud->id;
        
        if($solicitud->estado_solicitud == 'ENVIADA'){
          return view('tramites.espectaculos.cancelar', compact('estado','radicado', 'id'));

        }else if($solicitud->estado_solicitud == 'PENDIENTE' || $solicitud->estado_solicitud == 'ENTREGA_GARANTIA' || $solicitud->estado_solicitud == 'EVENTO_APROBADO' || $solicitud->estado_solicitud == 'DOCUMENTOS_ACTUALIZADOS'){

            if($hoy >= $date_3){

                Alert::error('Error', 'No es posible cancelar la solicitud N°-'.$solicitud->radicado.'debido a que se encuentra menos de 3 DIAS  de realizarse el evento');
               return redirect()->route('espectaculos.index');

            }else{
                return view('tramites.espectaculos.cancelar', compact('estado','radicado', 'id'));            }


        }else{
            Alert::error('Error', 'No es posible cancelar la solicitud N°-'.$solicitud->radicado.' debido a que se encuentra en una etapa avanzada del tramite');
            return redirect()->route('espectaculos.index');

        }    
        
       


    }

    public function cancelarSolicitud(Request $request){

        $solicitud = EspectaculosPublicos::FindOrFail($request->id);
         
        if($request->estado_solicitud=='ENVIADA'){
        $this->validate($request, [
            "observaciones" => 'required',            
        ]);
        }else{
            $this->validate($request, [
                "observaciones" => 'required',
                "arch_evento_cancelado"=>'required',        
            ]);

        }

        $date = date('Y-m-d');
        $ruta = null;
        if($request->arch_evento_cancelado || $request->arch_evento_cancelado != null){

            $adjunto1 = $request->file('arch_evento_cancelado')->storeAs('documentos_espectaculos/' . $solicitud->radicado, 'OFICIO-CANCELACION-' . $solicitud->radicado . '.pdf');
            $ruta = 'storage/documentos_espectaculos/' . $solicitud->radicado . '/OFICIO-CANCELACION-' . $solicitud->radicado . '.pdf'; 
        }

        $detalleCorreo = [
            'nombres' => $solicitud->nombre_o_razon,
            'mensaje' => 'Usted ha realizado una solicitud de cancelación ahora la oficina de impuestos    municipales de secretaria de hacienda validara esta solicitud',
            'Subject' => 'Solicitud de cancelación  N°' . $solicitud->radicado,
            'documento'=> 'NO',
            'fecha_pendiente' => null,
            'radicado'  => $solicitud->radicado,
            'estado' => 'SOLICITUD EN CANCELACION',
            'id'=> Crypt::encrypt($request->id)               

        ];

        $detalleCorreo_fun = [
            'nombres' => ' Funcionario xxx',
            'radicado' => $solicitud->radicado,
            'Subject' => 'Cancelacion de Solicitud No'.$solicitud->radicado,
            'documento'=> 'NO',
            'fecha_pendiente' => null,            
            'estado' => 'FUNCIONARIO',
            'mensaje'=> 'El solicitante cancela solicitud por motivos de: '.$request->observaciones.''
        ];
        $correo_funcionario = 'ojrincon@bucaramanga.gov.co';


       $solicitud->observaciones = 'MOTIVO DE CANCELACION: '.$request->observaciones;
       $solicitud->fecha_pendiente = null;
       $solicitud->fecha_actuacion = $date;
       $solicitud->adj_evento_cancelado = $ruta;      
       $solicitud->estado_solicitud = 'EVENTO_CANCELADO';

       if ($solicitud->save()) {

        $auditoria = Auditoria::create([
            'usuario' => $solicitud->numero_identificacion,
            'proceso_afectado' => 'Radicado-' . $solicitud->radicado,
            'tramite'=>'ESPECTACULOS PUBLICOS',
            'radicado'=> $solicitud->radicado,
            'accion' => 'actualización de estado del tramite a EVENTO_CANCELADO',
            'observacion'=>'Ciudadano realiza cancelacion de solicitud por motivo de: '.$request->observaciones

        ]);

        Mail::to($solicitud->email_responsable)->send(new NotificacionEspectaculos($detalleCorreo));
        Mail::to($correo_funcionario)->send(new NotificacionEspectaculos($detalleCorreo_fun));
     Alert::success('Operacion Exitosa', 'Se realizado la solicitud de cancelación exitosamente en el sistema');
        return redirect()->route('espectaculos.index');
    } else {

        Alert::error('Error', 'Ha ocurrido un error al registrar la cancelación de la solicitud');
        return redirect()->route('espectaculos.index');
    }       
    }

    public function pruebas(){

        $date= date('Y-m-d');
        // $unicos_espectaculos =EspectaculosPublicos::where('fecha_inicio_evento','<=', $date)->where('estado_solicitud', 'EVENTO_APROBADO')->where('evento_id', 2)->get();
        $solicitudes = NitEspectaculos::where('estado' ,'PAGADO')->select('espectaculo_id',DB::raw('count(id) as total'))->groupBy('espectaculo_id')->get();     
          if($solicitudes){
          foreach($solicitudes as $solicitud){
           $contador = NitEspectaculos::where('espectaculo_id', $solicitud->espectaculo_id)->count();

           if($contador==$solicitud->total){
            $espectaculo = EspectaculosPublicos::FindOrFail($solicitud->espectaculo_id);

            $auditoria = Auditoria::create([
    
                "usuario" => 'SISTEMA',
                "proceso_afectado" => 'Radicado-'.$espectaculo->radicado,
                "accion"=> 'Update a ESTADO PAGO_REALIZADO',
                "tramite"=>'ESPECTACULOS PUBLICOS',
                "radicado" => $espectaculo->radicado,
                "observacion" => 'Solicitud No '.$espectaculo->radicado. 'se aplico el pago correctamente el estado de la solicitud avanza a la siguiente etapa'    
    
            ]);

            $detalleCorreo = [
                'nombres' => $espectaculo->nombre_o_razon,
                'mensaje' => 'Su Solicitud No '.$espectaculo->radicado. ' se aplico el pago correctamente el estado de la solicitud avanza a la siguiente etapa',
                'Subject' => 'Pago Aplicado Rad-N°' . $espectaculo->radicado,
                'documento' => 'NO',                
                'fecha_pendiente' => null,
                'radicado'  => $espectaculo->radicado,
                'estado' => 'PAGO APLICADO',
                'id'=> Crypt::encrypt($espectaculo->id) 
            ];

            $detalleCorreo_fun = [
                'nombres' => ' Funcionario',
                'radicado' => $espectaculo->radicado,
                'Subject' => 'Pago Aplicado Solicitud Espectaculos Publicos No-'.$espectaculo->radicado,
                'documento'=> 'NO',
                'fecha_pendiente' => null,            
                'estado' => 'FUNCIONARIO',
                'mensaje'=> 'En La solicitud No -'. $espectaculo->radicado.' se aplico el pago correctamente'
            ];
            $correo_funcionario = 'julianrincon9230@gmail.com';
    
            Mail::to($espectaculo->email_responsable)->send(new NotificacionEspectaculos($detalleCorreo));
            Mail::to($correo_funcionario)->send(new NotificacionEspectaculos($detalleCorreo_fun));

            $espectaculo->observaciones ='Se aplico el pago correctamente el estado de la solicitud avanza a la siguiente etapa' ;
            $espectaculo->fecha_actuacion = $date;
            $espectaculo->estado_solicitud = 'PAGO_REALIZADO';                 
            $espectaculo->save(); 


           $updateNit= NitEspectaculos::where("espectaculo_id", $solicitud->espectaculo_id)->update(["estado" => "PAGO-FINALIZADO"]);
            
            
            
             
            
           }
           

        }
      }
        // return $unicos_espectaculos;
        // $datefin = '2021-11-30';
        // $dateInicio = '2021-11-27';

        // // $newDate = date("Ymd", strtotime($dateInicio));

        // // return $newDate;

        // $period = CarbonPeriod::create($dateInicio, $datefin);

        // foreach ($period as $date) {

        //     echo $date->format('Y-m-d').'<br>';
            

        // }

        // $date1 = Carbon::parse($datefin);
        // $date2 = Carbon::parse($dateInicio);

        // $diff = $date1->diffInDays($date2);

        // return $diff;

        




        // $buscaNit = DB::connection('sqlsrv')->table('nit')->where('nit', '987654')->where('estado', 'activo')->get();

        // if($buscaNit->count()<= 0){           
            
        //     $consulta = DB::connection('sqlsrv')->table('nit')->insertGetId(
        //         array('nit'=> '986551',
        //               'razon_social' => 'Andres productions',
        //               'estado'=> 'activo',
        //               'fecha_registro'=> date('Y-d-m H:i:s')
        //     ));

        //     return $consulta;
            
            // $buscarMae = DB::connection('sqlsrv')->table('mae_impuestos')->where('nit', '91078541')->where('id_modulo', 26)->get();
            
            // DB::connection('sqlsrv')->table('nit')->insert(
            //     array('nit'=> '91078541',
            //           'razon_social' => 'Andres productions',
            //           'estado'=> 'activo',
            //           'fecha_registro'=> date('Y-d-m H:i:s')
            // ));


            
            
                       
        }



    }


