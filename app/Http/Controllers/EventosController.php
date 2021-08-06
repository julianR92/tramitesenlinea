<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parametro;
use App\Barrio;
use App\Evento;
use App\Auditoria;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionEventos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class EventosController extends Controller
{
    public function index(){

        $Parametros1 = Parametro::where('ParNomGru', 'LETRA')->get();
        $Parametros2 = Parametro::where('ParNomGru','ABREDIR')->get();
        $Barrios = Barrio::all();

     return view('tramites.eventos.index', compact('Parametros1', 'Parametros2', 'Barrios'));

    }

    public function confirmacion()
    {
        return view('tramites.eventos.confirmacion');
    }

    public function end()
    {
        Session::flush();
        return redirect("/eventos-publicos");
    }

    public function consulta(Request $request){
    

        $QuerySolicitud = Evento::where($request->tipo_parametro, $request->parametro)->get();

        if ($QuerySolicitud->count() > 0) {
            
            // return $QuerySolicitud;
            return view('tramites.eventos.resultado', compact('QuerySolicitud'));
        } else {
            Alert::error('Ha Ocurrido un error', 'El Numero: .' . $request->parametro . ' no tiene registros asociados');
            return redirect()->route('parqueaderos.index');
        }
    }

    public function detalle($id){

        $solicitud = Evento::FindOrFail(Crypt::decrypt($id));

        $date = Carbon::now();
        $date1 = Carbon::parse($solicitud->fecha_pendiente);
        $diff = $date1->diffInDays($date); 
        
        if($solicitud->tipo_persona == 1){
            $responsable = $solicitud->nom_responsable.' '.$solicitud->ape_responsable;
        }else if($solicitud->tipo_persona ==2){
            $responsable = $solicitud->razon_social;
        }
        
        return view('tramites.eventos.detalle', compact('solicitud', 'diff','responsable'));



    }

    public function store(Request $request){
        
        if($request->tipo_persona == 1){
            $this->validate($request, [
                "nom_responsable"=> "required",
                "ape_responsable"=> "required"]);
                $responsable = $request->nom_responsable.' '.$request->ape_responsable;

        }else if($request->tipo_persona ==2){
            $this->validate($request, [
                "razon_social"=> "required" ]);
            $responsable = $request->razon_social;

        }

        $this->validate($request, [
                    
            "tipo_persona" => "required",
            "tipo_documento" => "required",
            "ide_responsable" => "required",            
            "dir_responsable_sol" => "required",
            "barrio_responsable_sol"=> "required",
            "tel_responsable" => "required",
            "email_responsable" => "required",
            "fecha_evento" => "required",
            "hora_inicio" => "required",
            "hora_fin" => "required",
            "ubicacion_evento" => "required",       
            "barrio_evento" => "required",
            "descripcion_evento" => "required",
            "adj_conceptoCMGRD_arch" => "required",
            "adj_cedulaRes_arch" => "required",          
            "adj_contratoArr_arch" => "required",
            "adj_conceptoTecAmb_arch"=> "required",
            "adj_certificadoPONAL_arch"=> "required",
            "adj_certificadoBomberos_arch"=> "required",
            "adj_hospitalaria_arch"=> "required",
            "adj_poliza_arch"=> "required",
            "adj_publicidad_arch"=> "required",
            "adj_certVigilancia_arch"=> "required",
            "adj_certificadoEMAB_arch"=> "required",
            "adj_derAutor_arch"=> "required",
            "tratamiento_datos"=> "required",
            "acepto_terminos"=> "required",
            "confirmo_mayorEdad"=> "required",
            "compartir_informacion"=> "required"

        ]);

       
        $ultimo_id = Evento::latest('id')->first();
        // return $ultimo_id;
        if (!$ultimo_id) {
            $idRadicado = 1;
        } else {
            $idRadicado = $ultimo_id->id + 1;
        }

        $radicado = date("Ymd") . $request->ide_responsable . $idRadicado; // numero radicado

        //documentos adjuntos

        if($request->adj_logisticaEvento || $request->adj_logisticaEvento != null){      
        $adjunto1 = $request->file('adj_logisticaEvento_arch')->storeAs('documentos_eventos/' . $radicado, 'Logistica-evento-' . $radicado . '.pdf');
        }else{
          $adjunto1 = false;
        }
      
        if($request->adj_autorizacionTra_arch || $request->adj_autorizacionTra_arch != null){ 
         $adjunto3 =  $request->file('adj_autorizacionTra_arch')->storeAs('documentos_eventos/' . $radicado, 'Autorizacion-transito-' . $radicado . '.pdf');
        }else{
            $adjunto3 = false;
        }
        
        $adjunto2 =  $request->file('adj_cedulaRes_arch')->storeAs('documentos_eventos/' . $radicado, 'Documento-identificacion-' . $radicado . '.pdf');

        $adjunto4 = $request->file('adj_contratoArr_arch')->storeAs('documentos_eventos/' . $radicado, 'Contrato-arrendamiento-' . $radicado . '.pdf');

        $adjunto5 = $request->file('adj_conceptoCMGRD_arch')->storeAs('documentos_eventos/' . $radicado, 'Concepto-CMGRD-' . $radicado . '.pdf');

        $adjunto6 = $request->file('adj_conceptoTecAmb_arch')->storeAs('documentos_eventos/' . $radicado, 'Concepto-Tecnico-ambiental-' . $radicado . '.pdf');

        $adjunto7 = $request->file('adj_certificadoPONAL_arch')->storeAs('documentos_eventos/' . $radicado, 'Concepto-PONAL-' . $radicado . '.pdf');

        $adjunto8 = $request->file('adj_certificadoBomberos_arch')->storeAs('documentos_eventos/' . $radicado, 'Concepto-BOMBEROS-' . $radicado . '.pdf');

        $adjunto9 = $request->file('adj_hospitalaria_arch')->storeAs('documentos_eventos/' . $radicado, 'Servicio-prehospitalario-' . $radicado . '.pdf');

        $adjunto10 =  $request->file('adj_poliza_arch')->storeAs('documentos_eventos/' . $radicado, 'Poliza-responsabilidad-civil-' . $radicado . '.pdf');

        $adjunto11 =  $request->file('adj_publicidad_arch')->storeAs('documentos_eventos/' . $radicado, 'Pago-publicidad-' . $radicado . '.pdf');

        $adjunto12 =  $request->file('adj_certVigilancia_arch')->storeAs('documentos_eventos/' . $radicado, 'Certificado-empresa-vigilancia-' . $radicado . '.pdf');

        $adjunto13 =  $request->file('adj_certificadoEMAB_arch')->storeAs('documentos_eventos/' . $radicado, 'Certificado-EMAB-' . $radicado . '.pdf');

        $adjunto14 =  $request->file('adj_derAutor_arch')->storeAs('documentos_eventos/' . $radicado, 'Certificado-derechos-autor-' . $radicado . '.pdf');
       
        //rutas de documentos
        if ($adjunto2 && $adjunto4 && $adjunto5 && $adjunto6 && $adjunto7 && $adjunto8 && $adjunto9 && $adjunto10 && $adjunto11 && $adjunto12 && $adjunto13 && $adjunto14 || $adjunto1 || $adjunto3) {

           if($adjunto1){
            $adj_logisticaEvento = 'storage/documentos_eventos/' . $radicado . '/Logistica-evento-' . $radicado . '.pdf';
            }else{
            $adj_logisticaEvento = null;
            }

            if($adjunto3){
              $adj_autorizacionTra = 'storage/documentos_eventos/' . $radicado . '/Autorizacion-transito-' . $radicado . '.pdf';
                }else{
                $adj_autorizacionTra = null;
            }

            $adj_cedulaRes = 'storage/documentos_eventos/' . $radicado . '/Documento-identificacion-' . $radicado . '.pdf';

            $adj_contratoArr = 'storage/documentos_eventos/' . $radicado . '/Contrato-arrendamiento-' . $radicado . '.pdf';

            $adj_conceptoCMGRD = 'storage/documentos_eventos/' . $radicado . '/Concepto-CMGRD-' . $radicado . '.pdf';

            $adj_conceptoTecAmb = 'storage/documentos_eventos/' . $radicado . '/Concepto-Tecnico-ambiental-' . $radicado . '.pdf';

            $adj_certificadoPONAL = 'storage/documentos_eventos/' . $radicado . '/Concepto-PONAL-' . $radicado . '.pdf';

            $adj_certificadoBomberos = 'storage/documentos_eventos/' . $radicado . '/Concepto-BOMBEROS-' . $radicado . '.pdf';

            $adj_hospitalaria = 'storage/documentos_eventos/' . $radicado . '/Servicio-prehospitalario-' . $radicado . '.pdf';

            $adj_poliza = 'storage/documentos_eventos/' . $radicado . '/Poliza-responsabilidad-civil-' . $radicado . '.pdf';

            $adj_publicidad = 'storage/documentos_eventos/' . $radicado . '/Pago-publicidad-' . $radicado . '.pdf';

            $adj_certVigilancia = 'storage/documentos_eventos/' . $radicado . '/Certificado-empresa-vigilancia-' . $radicado . '.pdf';

            $adj_certificadoEMAB = 'storage/documentos_eventos/' . $radicado . '/Certificado-EMAB-' . $radicado . '.pdf';

            $adj_derAutor = 'storage/documentos_eventos/' . $radicado . '/Certificado-derechos-autor-' . $radicado . '.pdf';

            // add al requests

            $request->request->add([
                'tipo_evento'=>1,
                'radicado' => $radicado,
                'dir_responsable'=>$request->dir_responsable_sol.' '.$request->barrio_responsable_sol,
                'adj_logisticaEvento' => $adj_logisticaEvento,            
                'adj_autorizacionTra' => $adj_autorizacionTra,
                'adj_cedulaRes' => $adj_cedulaRes,
                'adj_contratoArr'=> $adj_contratoArr,
                'adj_conceptoCMGRD'=> $adj_conceptoCMGRD,
                'adj_conceptoTecAmb'=> $adj_conceptoTecAmb,
                'adj_certificadoPONAL'=> $adj_certificadoPONAL,
                'adj_certificadoBomberos'=> $adj_certificadoBomberos,
                'adj_hospitalaria'=> $adj_hospitalaria,
                'adj_poliza'=> $adj_poliza,
                'adj_publicidad'=>$adj_publicidad,
                'adj_certVigilancia'=> $adj_certVigilancia,
                'adj_certificadoEMAB'=> $adj_certificadoEMAB,
                'adj_derAutor'=> $adj_derAutor,
                'estado_solicitud' => 'ENVIADA',
                
            ]);

            $detalleCorreo = [
                'nombres' => $responsable,
                'radicado' => $radicado,
                'Subject' => 'Envió de Solicitud Permisos para Espectaculos Públicos',
                'documento'=> 'NO',
                'fecha_pendiente' => null,            
                'estado' => null,
                'mensaje'=> null
            ];

            $solicitud = $request->all();
            // return $solicitud;
            $saveSolicitud = Evento::create($solicitud);

            if ($saveSolicitud) {

                //auditoria
                $auditoria = Auditoria::create([
                    'usuario' => $request->ide_responsable,
                    'proceso_afectado'=> 'Radicado-'.$radicado,
                    'tramite'=> 'PERMISOS PARA ESPECTACULOS PUBLICOS',
                    'radicado'=> $radicado,
                    'accion'=>'update a estado ENVIADO',
                    'observacion'=> 'El señor(es) '.$responsable. 'realiza una solicitud de permisos para espectaculos publicos'
    
                ]);
    
                // envio de correo                
                Mail::to($request->email_responsable)->queue(new NotificacionEventos($detalleCorreo));
    
                $request->session()->flash('radicado_id', $radicado);
                return redirect()->route('eventos.confirmacion');
            } else {
                Alert::error('Ha Ocurrido un error', 'Ha ocurrido un error en en registrar su solicitud');
                return redirect()->route('eventos.index');
            }


        }else{

            
            Alert::error('Ha Ocurrido un error', 'Los archivos no se han cargado correctamente vuelva intentarlo');
            return redirect()->route('eventos.index');


        }

    }

    public function updateDocs(Request $request){

        $solicitud = Evento::FindOrFail($request->id);
        $contador = 0;

        if($solicitud->tipo_persona == 1){            
            $responsable = $solicitud->nom_responsable.' '.$solicitud->ape_responsable;
            }else if($solicitud->tipo_persona ==2){           
                $responsable = $solicitud->razon_social;
            }

        if($request->adj_cedulaRes_arch){
            $adjunto1 =  $request->file('adj_cedulaRes_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Documento-identificacion-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto1 = false;
        }   

        if($request->adj_logisticaEvento_arch){
            $adjunto2 =  $request->file('adj_logisticaEvento_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Logistica-evento-' . $solicitud->radicado . '.pdf');
            //guarda ruta
            $ruta_adj_logisticaEvento = 'storage/documentos_eventos/' . $solicitud->radicado . '/Logistica-evento-' . $solicitud->radicado . '.pdf';
            $solicitud->adj_logisticaEvento = $ruta_adj_logisticaEvento;            
            $contador++;
        }else{
            $adjunto2 = false;
        } 

        if($request->adj_autorizacionTra_arch){
            $adjunto3 =  $request->file('adj_autorizacionTra_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Autorizacion-transito-' . $solicitud->radicado . '.pdf');
            // guarda ruta
            $ruta_adj_autorizacionTra = 'storage/documentos_eventos/' . $solicitud->radicado . '/Autorizacion-transito-' . $solicitud->radicado . '.pdf';
            $solicitud->adj_autorizacionTra = $ruta_adj_autorizacionTra;

            $contador++;
        }else{
            $adjunto3 = false;
        } 

        if($request->adj_contratoArr_arch){
            $adjunto4 =  $request->file('adj_contratoArr_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Contrato-arrendamiento-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto4 = false;
        } 

        if($request->adj_conceptoCMGRD_arch){
            $adjunto5 =  $request->file('adj_conceptoCMGRD_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Concepto-CMGRD-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto5 = false;
        } 

        
        if($request->adj_conceptoTecAmb_arch){
            $adjunto6 =  $request->file('adj_conceptoTecAmb_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Concepto-Tecnico-ambiental-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto6 = false;
        } 

        if($request->adj_certificadoPONAL_arch){
            $adjunto7 =  $request->file('adj_certificadoPONAL_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Concepto-PONAL-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto7 = false;
        } 

        if($request->adj_certificadoBomberos_arch){
            $adjunto8 =  $request->file('adj_certificadoBomberos_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Concepto-BOMBEROS-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto8 = false;
        } 

        if($request->adj_hospitalaria_arch){
            $adjunto9=  $request->file('adj_hospitalaria_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Servicio-prehospitalario-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto9 = false;
        } 

        if($request->adj_publicidad_arch){
            $adjunto10=  $request->file('adj_publicidad_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Pago-publicidad-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto10 = false;
        } 

        if($request->adj_certVigilancia_arch){
            $adjunto11=  $request->file('adj_certVigilancia_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Certificado-empresa-vigilancia-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto11 = false;
        } 

        if($request->adj_certificadoEMAB_arch){
            $adjunto12=  $request->file('adj_certificadoEMAB_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Certificado-EMAB-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto12 = false;
        } 

        if($request->adj_derAutor_arch){
            $adjunto13=  $request->file('adj_derAutor_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Certificado-derechos-autor-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto13 = false;
        } 

        if($request->adj_poliza_arch){
            $adjunto14=  $request->file('adj_poliza_arch')->storeAs('documentos_eventos/' . $solicitud->radicado, 'Poliza-responsabilidad-civil-' . $solicitud->radicado . '.pdf');
            $contador++;
        }else{
            $adjunto14 = false;
        }

        if($adjunto1 || $adjunto2 || $adjunto3 || $adjunto4 || $adjunto5 || $adjunto6 || $adjunto7 || $adjunto8 || $adjunto9 || $adjunto10 || $adjunto11 || $adjunto12 || $adjunto13 || $adjunto14){

            //auditoria
            $auditoria = Auditoria::create([
              'usuario' => $solicitud->ide_responsable,
              'proceso_afectado'=> 'Radicado-'.$solicitud->radicado,
              'tramite'=> 'PERMISOS PARA ESPECTACULOS PUBLICOS',
              'radicado'=> $solicitud->radicado,
              'accion'=>'update de documentos',
              'observacion'=> 'El señor(es) '.$responsable.'realizan actulización de documentos dentro de las fechas permitidas'

          ]);

          $solicitud->act_documentos = "SI";
          $solicitud->save();            
          Alert::success('Operacion exitosa', 'Se han cargado '.$contador.' archivo(s) en el sistema');
          return redirect()->route('eventos.index');

      }else{
          Alert::error('Error', 'No se ha realizado la carga de los archivos en el sistema');
          return redirect()->route('eventos.index');
      }

    }

}
