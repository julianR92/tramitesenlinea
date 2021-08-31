<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barrio;
use App\Orientacion;
use App\Vereda;
use App\Instituciones;
use App\Metrolinea;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MetrolineaController extends Controller
{
    public function index(){

        $Barrios = Barrio::all();
        $instituciones = Instituciones::where('tipo', '1')->Orwhere('tipo','4')->get();
        $orientacion_sexual = Orientacion::all();
        $discapacidades = DB::table('tipo_discapacidad')->get();
        
       
       return view('tramites.metrolinea.index', compact('Barrios', 'orientacion_sexual','instituciones', 'discapacidades'));

    }

    public function confirmacion()
    {
        return view('tramites.metrolinea.confirmacion');

    }

    public function end()
    {
        Session::flush();
        return redirect("https://www.bucaramanga.gov.co/");
    }
    

    public function store(Request $request){
      
        // validacion campos requeridos
      $this->validate($request, [
        "tipo_poblacion" => "required",
        "nombre_usuario" => "required",
        "apellido_usuario" => "required",        
        "fecha_nacimiento" => "required",
        "estado_civil" => "required",
        "nivel_estudios" => "required",
        "tipo_documento" => "required",
        "documento_usuario" => "required",
        "sexo" => "required",
        "telefono_usuario" => "required",
        "email_usuario" => "required",
        "id_municipio" => "required",       
        "barrio" => "required", 
        "corregimiento"=> "required",       
        "direccion_usuario" => "required",        
        "ruta_frecuente" => "required",
        "estrato_socioeconomico" => "required",
        "trabaja_actualmente"=> "required",
        "tratamiento_datos"=> "required",
        "acepto_terminos"=> "required",
        "confirmo_mayorEdad"=> "required",
        "compartir_informacion"=> "required",
        "edad"=> "required",
        "archivo_documentoIdentidad"=> "required",
        "archivo_certiVencidad"=> "required",
        
    ]);

    $ultimo_id = Metrolinea::latest('id')->first();
    // return $ultimo_id;
    if (!$ultimo_id) {
        $idRadicado = 1;
    } else {
        $idRadicado = $ultimo_id->id + 1;
    }
    $radicado = $request->documento_usuario . $idRadicado; // numero de solicitud

    $adjunto1 = $request->file('archivo_documentoIdentidad')->storeAs('documentos_metrolinea/' . $radicado, 'documento-de-identidad-' . $radicado . '.pdf');


    $adjunto2 = $request->file('archivo_certiVencidad')->storeAs('documentos_metrolinea/' . $radicado, 'certificado-vecindad-' . $radicado . '.pdf');
    
    if($request->archivo_certificadoEstudio || $request->archivo_certificadoEstudio != null){  
    $adjunto3 = $request->file('archivo_certificadoEstudio')->storeAs('documentos_metrolinea/' . $radicado, 'certificado-estudio-' . $radicado . '.pdf');
    }else{
        $adjunto3 = false;
    }

    if ($adjunto1 && $adjunto2 || $adjunto3) {

        $adj_documentoIdentidad = 'http://tramitesenlinea.test/storage/documentos_metrolinea/' . $radicado . '/documento-de-identidad-' . $radicado . '.pdf';        

        $adj_certiVecindad = 'http://tramitesenlinea.test/storage/documentos_metrolinea/' . $radicado . '/certificado-vecindad-' . $radicado . '.pdf';
         
        if($adjunto3){
        $adj_certificadoEstudio = 'http://tramitesenlinea.test/storage/documentos_metrolinea/' . $radicado . '/certificado-estudio-' . $radicado . '.pdf';
        }else{
         $adj_certificadoEstudio = null;  
        }

        $request->request->add([
            'numero_solicitud' => $radicado,
            'adj_documentoIdentidad' => $adj_documentoIdentidad,            
            'adj_certiVecindad' => $adj_certiVecindad,
            'adj_certificadoEstudio' => $adj_certificadoEstudio,
            'estado_solicitud' => 'ENVIADA'
        ]);

        $request->merge([
            "ruta_frecuente" => implode("-", $request->ruta_frecuente)
        ]);

        $solicitud = $request->all();
        // return $solicitud;
        $saveSolicitud = Metrolinea::create($solicitud);

        if ($saveSolicitud) {

            // envio de correo                
            // Mail::to($request->email_responsable)->queue(new NotificacionParqueaderos($detalleCorreo));
            // Mail::to($correo_funcionario)->queue(new NotificacionParqueaderos($detalleCorreo_fun));

            $request->session()->flash('radicado_id', $radicado);
            return redirect()->route('metrolinea.confirmacion');
        } else {
            Alert::error('Ha Ocurrido un error', 'Ha ocurrido un error en en registrar su solicitud');
            return redirect()->route('metrolinea.index');
        }


    }else {

        Alert::error('Ha Ocurrido un error', 'Los archivos no se han cargado correctamente vuelva intentarlo');
        return redirect()->route('metrolinea.index');
    }
     

    }
    public function consulta(Request $request){

        $QuerySolicitud = Metrolinea::where($request->tipo_parametro, $request->parametro)->get();

        if ($QuerySolicitud->count() > 0) {
            
            // return $QuerySolicitud;
            return view('tramites.metrolinea.resultado', compact('QuerySolicitud'));
        } else {
            Alert::error('Ha Ocurrido un error', 'El Numero: .' . $request->parametro . ' no tiene registros asociados');
            return redirect()->route('metrolinea.index');
        }
    }
}
