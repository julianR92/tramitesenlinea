<?php

namespace App\Http\Controllers;

use App\EspacioPublico;
use App\Auditoria;
use App\Parqueadero;
use App\Publicidad;
use App\PublicidadDetalle;
use App\PublicidadConceptos;
use App\AuditoriaParqueadero;
use App\Barrio;
use App\Pot;
use App\Vereda;
use App\PlanosTipoLicencia;
use App\PlanosModalidades;
use App\PrestamoPlanos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnvioNotificacion;
use App\Mail\EnvioNotificacionPlanos;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class PlaneacionController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth', ['except' => ['indexPot', 'barriosComunas', 'veredaCorregimiento', 'validacionDocumento', 'potStore', 'confirmacionPot', 'planosTipoLicenciaGet', 'planosModalidadesGet', 'planosCreate', 'planosConsultar']]);
	}

	public function index()
	{

		return view('tramites.planeacion.index');
	}

	public function espacioIndex()
	{
		$sEnviadas = EspacioPublico::where('estado_solicitud', 'ENVIADA')->get();
		$sProgreso = EspacioPublico::where('estado_solicitud', 'VISITA-TECNICA')->get();
		$sPendientes = EspacioPublico::where('estado_solicitud', 'PENDIENTE')->get();
		$sEstudio = EspacioPublico::where('estado_solicitud', 'EN ESTUDIO')->get();
		$sAprobadas = EspacioPublico::where('estado_solicitud', 'APROBADA')->get();
		$sRechazadas = EspacioPublico::where('estado_solicitud', 'RECHAZADA')->get();
		$porCerrar =  EspacioPublico::where('estado_solicitud', 'PENDIENTE')->where('fecha_pendiente', '<', DB::raw('DATE_ADD(NOW(),INTERVAL 5 DAY)'))->get()->count();
		$count_enviadas = $sEnviadas->count();
		$count_progreso = $sProgreso->count();
		$count_pendientes = $sPendientes->count();
		$count_aprobadas = $sAprobadas->count();
		$count_rechazadas = $sRechazadas->count();
		$count_enEstudio = $sEstudio->count();

		return view('tramites.planeacion.espacio.index', compact('sEnviadas', 'sProgreso', 'sPendientes', 'sAprobadas', 'sRechazadas', 'count_enviadas', 'count_progreso', 'count_pendientes', 'count_aprobadas', 'count_rechazadas', 'porCerrar', 'sEstudio', 'count_enEstudio'));
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
				'estado' => $request->estado_solicitud
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
					'proceso_afectado' => 'Radicado-' . $datos->radicado,
					'tramite' => 'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
					'radicado' => $datos->radicado,
					'accion' => 'update a estado ' . $request->estado_solicitud,
					'observacion' => $request->observaciones_solicitud

				]);

				Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
				Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
				return redirect()->route('espacio.index');
			} else {

				Alert::error('Error', 'Ha ocurrido un erro al registrar la actualizacion de la solicitud');
				return redirect()->route('espacio.index');
			}
		} elseif ($request->estado_solicitud == 'VISITA-TECNICA') {

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
				'Subject' => 'Visita para solicitud de Intervención de Espacio Publico N°' . $datos->radicado,
				'documento' => 'NO',
				'fecha_pendiente' => $date_30,
				'radicado'  => $datos->radicado,
				'estado' => $request->estado_solicitud
			];
			// actualizar

			if ($request->documento_visita || $request->documento_visita != null) {
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
					'proceso_afectado' => 'Radicado-' . $datos->radicado,
					'tramite' => 'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
					'radicado' => $datos->radicado,
					'accion' => 'update a estado ' . $request->estado_solicitud,
					'observacion' => $request->observaciones_solicitud


				]);

				Mail::to($datos->email_responsable)->send(new EnvioNotificacion($detalleCorreo));
				Alert::success('Operacion Exitosa', 'Se actualizado exitosamente el estado del tramite en el sistema');
				return redirect()->route('espacio.index');
			} else {

				Alert::error('Error', 'Ha ocurrido un erro al registrar la actualizacion de la solicitud');
				return redirect()->route('espacio.index');
			}
		} elseif ($request->estado_solicitud == 'EN ESTUDIO') {

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

			if ($request->documento_visita || $request->documento_visita != null) {
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
					'proceso_afectado' => 'Radicado-' . $datos->radicado,
					'tramite' => 'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
					'radicado' => $datos->radicado,
					'accion' => 'update a estado ' . $request->estado_solicitud,
					'observacion' => $request->observaciones_solicitud


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

				if ($request->documento_visita || $request->documento_visita != null) {
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
						'proceso_afectado' => 'Radicado-' . $datos->radicado,
						'tramite' => 'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
						'radicado' => $datos->radicado,
						'accion' => 'update a estado ' . $request->estado_solicitud,
						'observacion' => $request->observaciones_solicitud

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
		} elseif ($request->estado_solicitud == 'RECHAZADA') {

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
						'proceso_afectado' => 'Radicado-' . $datos->radicado,
						'tramite' => 'LICENCIA DE INTERVENCION DE ESPACIO PUBLICO PARA LOCALIZACION DE EQUIPAMIENTO',
						'radicado' => $datos->radicado,
						'accion' => 'update a estado ' . $request->estado_solicitud,
						'observacion' => $request->observaciones_solicitud

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

	public function documentSolicitud($id)
	{

		$solicitud = EspacioPublico::findOrFail($id);
		// $pdf = App::make('dompdf.wrapper');
		$pdf = PDF::loadView('tramites.planeacion.espacio.document', compact('solicitud'));
		return $pdf->stream();
	}

	public function indexParqueaderos()
	{

		$sEnRevision = Parqueadero::where('estado_solicitud', 'REVISION-PLANEACION')->get();
		$contador_revision = $sEnRevision->count();
		$sRevisadas = AuditoriaParqueadero::where('estado_solicitud', 'RESPUESTA-PLANEACION')->get();
		$contador_revisadas = $sRevisadas->count();
		$porCerrar =  Parqueadero::where('estado_solicitud', 'REVISION-PLANEACION')->where('fecha_pendiente_planeacion', '<', DB::raw('DATE_ADD(NOW(),INTERVAL 5 DAY)'))->get()->count();


		return view('tramites.planeacion.parqueaderos.index', compact('sEnRevision', 'contador_revision', 'porCerrar', 'sRevisadas', 'contador_revisadas'));
	}

	public function parqueaderoDetalle($id)
	{

		$solicitud = Parqueadero::findOrFail($id);
		return view('tramites.planeacion.parqueaderos.detalle', compact('solicitud'));
	}
	public function parqueaderoDetalleAuditoria($id)
	{

		$solicitud = AuditoriaParqueadero::findOrFail($id);
		return view('tramites.planeacion.parqueaderos.auditoria', compact('solicitud'));
	}

	//publicidad
	public function publicidadIndex()
	{

		$sEnviadas = Publicidad::where('estado_solicitud', 'REVISION-CONCEPTOS-PLANEACION')->get();
		$porCerrar = "";
		$porCerrarPlaneacion = "";
		$count_enviadas = $sEnviadas->count();
		return view('tramites.planeacion.publicidad.listar_solicitudes', compact('sEnviadas', 'count_enviadas'));
	}

	public function publicidadDetalle($id)
	{
		$solicitud = Publicidad::findOrFail($id);
		$detalle = PublicidadDetalle::join('barrio', 'barrio.codigo', '=', 'publicidad_detalle.barrio_aviso')->where('publicidad_id', $id)->get()->first();
		return view('tramites.planeacion.publicidad.detalle', compact('solicitud', 'detalle'));
	}

	public function publicidadUpdate(Request $request)
	{
		$datos = Publicidad::findOrFail($request->id);
		$adjunto = new PublicidadConceptos;

		switch ($request->modalidad) {
			case 'VALLAS':

				$this->validate($request, [
					"observacion_solicitud" => 'required',
					"adj_concepto_planeacion" => 'required'
				]);

				if ($request->adj_concepto_planeacion || $request->adj_concepto_planeacion != null) {
					$adj_concepto_planeacion =  $request->file('adj_concepto_planeacion')->storeAs('documentos_publicidad/' . $datos->radicado, 'adj_concepto_planeacion-' . $datos->radicado . '.pdf');
					$adj_concepto_planeacion_rut = 'storage/documentos_publicidad/' . $datos->radicado . '/adj_concepto_planeacion-' . $datos->radicado . '.pdf';
				} else {
					$adj_concepto_planeacion = null;
				}

				$date = date('Y-m-d');
				$date_30 = date("Y-m-d", strtotime($date . "+15 Weekday"));

				$detalleCorreo = [
					'nombres' => 'Israel Andres Barragan Jerez',
					'mensaje' => $request->observacion_solicitud,
					'Subject' => 'Envio Concepto Técnico Publicidad Exterior N°' . $datos->radicado,
					'documento' => 'NO',
					'fecha_pendiente' => $date_30,
					'radicado'  => $datos->radicado,
					'estado' => 'FUNCIONARIO'
				];

				// actualizar datos
				$datos->estado_solicitud = 'REVISION-CONCEPTOS-SALUD';
				$datos->observacion_solicitud = $request->observacion_solicitud;
				$datos->fecha_actuacion = $date;
				$datos->fecha_pendiente_salud = $date_30;

				$adjunto->publicidad_id = $request->id;
				$adjunto->adj_concepto_planeacion = $adj_concepto_planeacion_rut;

				//$correo_responsable = ['fzuluaga@bucaramanga.gov.co', 'pdiaz@bucaramanga.gov.co'];
				$correo_responsable = ['julianrincon9230@gmail.com', 'ojrincon@bucaramanga.gov.co'];

				if ($datos->save()) {
					$adjunto->save();
					//auditoria
					$auditoria = Auditoria::create([
						'usuario' => $request->username,
						'proceso_afectado' => 'Radicado-' . $datos->radicado,
						'tramite' => 'PUBLICIDAD EXTERIOR',
						'radicado' => $datos->radicado,
						'accion' => 'update a estado REVISION-CONCEPTOS-SALUD',
						'observacion' => $request->observacion_solicitud
					]);

					Mail::to($correo_responsable)->queue(new NotificacionPublicidad($detalleCorreo));
					Alert::success('Operacion Exitosa', 'Se ha actualizado exitosamente el estado del tramite en el sistema');
					return redirect()->route('planeacion.publicidad.index');
				} else {
					Alert::error('Error', 'Ha ocurrido un erro al registrar la actualización de la solicitud');
				}

				break;

			default:
				# code...
				break;
		}
	}

	//fin publicidad

	//POT
	public function indexPot()
	{
		$conteo = Pot::all()->count();
		$Barrios = Barrio::all();
		$veredas = Vereda::all();
		return view('tramites.planeacion.pot.index', compact('Barrios', 'conteo', 'veredas'));
	}

	public function barriosComunas(Request $request)
	{

		$dataBarrio = Barrio::where('codigo', $request->codigo)->first();
		$comuna = DB::table('comuna')->where('codigo', $dataBarrio->codigo_comuna)->first();
		$resp = ["success" => true, "respuesta" => $comuna];
		return response()->json($resp);
	}

	public function veredaCorregimiento(Request $request)
	{
		$dataVereda = Vereda::where('codigo', $request->codigo)->first();
		$corregimiento = DB::table('corregimiento')->where('codigo', $dataVereda->codigo_corregimiento)->first();
		$resp = ["success" => true, "respuesta" => $corregimiento];
		return response()->json($resp);
	}

	public function validacionDocumento(Request $request)
	{
		$dataCount = Pot::where('documento_usuario', $request->codigo)->count();
		$resp = ["success" => true, "respuesta" => $dataCount];
		return response()->json($resp);
	}

	public function potStore(Request $request)
	{
		$this->validate($request, [
			"documento_usuario" => "required|unique:opinion_pot",
			"nombre_usuario" => "required",
			"apellido_usuario" => "required",
			"edad" => "required",
			"correo_electronico" => "required",
			"residencia" => "required",
			"tema" => "required",
			"observacion" => "required|max:300",
			"tratamiento_datos" => "required",
			"acepto_terminos" => "required",
			"confirmo_mayorEdad" => "required",
			"compartir_informacion" => "required"

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

	//funciones de consulta de uso de suelo

	//modulo consulta de uso de suelo

	public function indexUsoSuelo()
	{
		$barrios =  DB::connection('mysql5')->table('barrio')->get();
		$unidades =  DB::connection('mysql6')->table('Unidad')->get();

		return view('tramites.planeacion.uso-suelo.reportes', compact('barrios', 'unidades'));
	}

	public function queryReport(Request $request)
	{
		if (
			!$request->estado_solicitud &&
			!$request->radicado &&
			!$request->identificacion &&
			!$request->fecha_inicial &&
			!$request->fecha_final &&
			!$request->codigo &&
			!$request->direccion_busqueda &&
			!$request->unidad &&
			!$request->barrio
		) {
			Alert::info('Atencion!', 'Todos los campos de busqueda estan vacios');
			return redirect()->route('planeacion.uso-suelo.index');
		}
		if ($request->fecha_inicial && $request->fecha_final) {
			if ($request->fecha_inicial > $request->fecha_final) {
				Alert::info('Atencion!', 'La fecha inicial no puede ser mayor que la fecha final');
				return redirect()->route('planeacion.uso-suelo.index');
			}
		}

		$estado = ($request->estado_solicitud) ? $request->estado_solicitud : '%';
		$radicado = ($request->radicado) ? $request->radicado : '%';
		$identificacion = ($request->identificacion) ? $request->identificacion : '%';
		$fecha_inicial = ($request->fecha_inicial) ? $request->fecha_inicial : '2019-01-01';
		$fecha_final = ($request->fecha_final) ? $request->fecha_final : '2050-12-31';
		$codigo = ($request->codigo) ? $request->codigo : '%';
		$direccion = ($request->direccion_busqueda) ? trim($request->direccion_busqueda) : '%';
		$barrio = ($request->barrio) ? $request->barrio : '%';
		$unidad = ($request->unidad) ? $request->unidad : '%';

		$sql = "SELECT id_concepto, 
		radicado, 
		nombre_solicitante, 
		tipo_documento, 
		documento_solicitante, 
		fecha_expedicion, 
		direccion_solicitante, 
		direccion_ipu, 
		correo_solicitante, 
		telefono_solicitante, 
		documento, 
		codigo_predial, 
		unidad, 
		area_actividad, 
		barrio, 
		estrato, 
		ConEstado, 
		ConZonNor, 
		ConAreCon, 
		ConTraUrb, 
		ConMotInc, 
		ConAceTer, 
		ConAceComInf, 
		ConAutTra, 
		ConMayEda, 
		ConFecReg, 
		ConHorReg
		FROM usosuelo.concepto_uso
		WHERE ConEstado LIKE '$estado' 
		AND radicado LIKE '$radicado'
		AND documento_solicitante LIKE '$identificacion'
		AND IFNULL(direccion_ipu,'')  LIKE '%$direccion%'
		AND IFNULL(barrio,'') LIKE '$barrio'
		AND codigo_predial LIKE '$codigo'
		AND unidad LIKE '$unidad'
		AND ConFecReg BETWEEN '$fecha_inicial' AND '$fecha_final'
		LIMIT 5000";


		$query = DB::connection('mysql6')->select($sql);
		if ($query) {
			return view('tramites.planeacion.uso-suelo.export', compact('query'));
		} else {
			Alert::info('Atencion!', 'No se encontraron resultados para esta búsqueda');
			return redirect()->route('planeacion.uso-suelo.index');
		}
	}

	public function usoDetalle($id)
	{

		$solicitud = DB::connection('mysql6')->table('concepto_uso')->where('id_concepto', $id)->get();
		// return $solicitud;   
		return view('tramites.planeacion.uso-suelo.detalle', compact('solicitud'));
	}

	public function usoTablero()
	{
		return view('tramites.planeacion.uso-suelo.main');
	}

	public function planosTipoLicenciaGet()
	{
		$tipo_licencia = PlanosTipoLicencia::all();
		return response()->json(['tipo_licencia' => $tipo_licencia]);
	}

	public function planosModalidadesGet($tipo_licencia_id)
	{
		$modalidades = PlanosModalidades::where('tipo_licencia_id', $tipo_licencia_id)->get();
		return response()->json(['modalidades' => $modalidades]);
	}

	public function planosIndex()
	{

		$en_tramite = PrestamoPlanos::where('estado_solicitud', 'En trámite')->count();
		$para_entrega = PrestamoPlanos::where('estado_solicitud', 'Para entrega')->count();
		$rechazado = PrestamoPlanos::where('estado_solicitud', 'Rechazado')->count();
		$finalizado = PrestamoPlanos::where('estado_solicitud', 'Finalizado')->count();

		// Puedes almacenarlos en un array o hacer lo que necesites
		$conteos = [
			'En trámite' => $en_tramite,
			'Para entrega' => $para_entrega,
			'Rechazado' => $rechazado,
			'Finalizado' => $finalizado,
		];
		$datos = PrestamoPlanos::with('tipoLicencia', 'modalidadLicencia')->orderByRaw("CASE WHEN estado_solicitud = 'Finalizado' THEN 1 ELSE 0 END")  // Pone los 'Finalizado' al final
			->orderBy('id', 'desc')->get();
		return view('tramites.planeacion.planos.index', compact('datos', 'conteos'));
	}

	public function planosConsultar(Request $request)
	{
		$documento = $request->input('documento');
		$radicado = $request->input('radicado');

		$datos = PrestamoPlanos::with('tipoLicencia', 'modalidadLicencia')
			->when($documento, function ($query, $documento) {
				return $query->where('documento_identificacion', $documento);
			})
			->when($radicado, function ($query, $radicado) {
				return $query->where('radicado', $radicado);
			})
			->get();
		return response()->json(['success' => true, 'message' => 'Datos encontrados', 'data' => $datos]);
	}

	public function planosDetail($id)
	{
		$datos = PrestamoPlanos::with(['tipoLicencia', 'modalidadLicencia'])->findOrFail($id);
		$barrio = Barrio::where('codigo', $datos->barrio)->first();
		return view('tramites.planeacion.planos.detalle', compact('datos', 'barrio'));
	}

	public function planosUpdate(Request $request)
	{
		$datos = PrestamoPlanos::find($request->id);

		$datos->estado_solicitud = $request->estado_solicitud;
		$datos->observaciones = $request->planos_observaciones;
		$datos->updated_at = date('Y-m-d H:i:s');
		$detalleCorreo = [
			'nombres' => $datos->nombre_solicitante,
			'mensaje' => 'Su solicitud de copia de planos tiene una novedad, a cambiado al estado: ' . $request->estado_solicitud,
			'Subject' => 'Solicitud de copia de planos N°' . $datos->radicado,
			'documento' => 'NO',
			'radicado'  => $datos->radicado,
			'estado' => $request->estado_solicitud
		];
		Mail::to($datos->correo_electronico)->send(new EnvioNotificacionPlanos($detalleCorreo));
		$datos->save();

		return redirect()->route('planeacion.prestamo-planos.index');
	}

	public function planosCreate(Request $request)
	{
		try {
			$datos = new PrestamoPlanos();
			$ultimo_id = PrestamoPlanos::latest('id')->first();
			if (!$ultimo_id) {
				$radicado = 1;
			} else {
				$radicado = $ultimo_id->id + 1;
			}
			$radicado = date('Ymd') . '-' . $radicado;
			$file = $request->file('documento_certificado_libertad');

			$extension = $file->getClientOriginalExtension();
			$ruta = 'public/prestamo_planos/' . $request->documento_identificacion;
			$archivo = $file->storeAs($ruta,  $radicado . '.' . $extension);
			if ($archivo) {
				$datos->radicado = $radicado;
				$datos->fecha_solicitud = date('Y-m-d');
				$datos->nombre_solicitante = $request->nombre_solicitante;
				$datos->documento_identificacion = $request->documento_identificacion;
				$datos->direccion_solicitante = $request->direccion_solicitante;
				$datos->correo_electronico = $request->correo_electronico;
				$datos->telefono = $request->telefono;
				$datos->numero_licencia = $request->numero_licencia;
				$datos->numero_predial = $request->numero_predial;
				$datos->tipo_licencia = $request->tipo_licencia;
				$datos->modalidad_licencia = $request->modalidad_licencia;
				$datos->direccion_predio = $request->direccion_predio;
				$datos->barrio = $request->barrio;
				$datos->numero_folio_matricula_inmobiliaria = $request->numero_folio_matricula_inmobiliaria;
				$datos->propietario_predio = $request->propietario_predio;
				$datos->nombre_constructor = $request->nombre_constructor;
				$datos->fecha_aproximada_documentacion = $request->fecha_aproximada_documentacion;
				$datos->documentos_requeridos = $request->documentos_requeridos;
				$datos->motivo_destinacion = $request->motivo_destinacion;
				$datos->estado_solicitud = "RADICADO";
				$datos->acepta_terminos = $request->autoriza_notificacion;
				$datos->autoriza_notificacion = $request->autoriza_notificacion;
				$datos->created_at = date('Y-m-d H:i:s');
				$datos->documento_certificado_libertad = 'storage/public/prestamo_planos/' . $request->documento_identificacion . '/' . $radicado .  '.' . $extension;
				$datos->save();

				$detalleCorreo = [
					'nombres' => $datos->nombre_solicitante,
					'mensaje' => 'Se ha registrado una solicitud de copia de planos con el No: ' . $datos->radicado,
					'Subject' => 'Solicitud de copia de planos N°' . $datos->radicado,
					'documento' => 'NO',
					'radicado'  => $datos->radicado,
					'estado' => $datos->estado_solicitud
				];
				Mail::to('amelonb@bucaramanga.gov.co')->send(new EnvioNotificacionPlanos($detalleCorreo));
				return response()->json(['success' => true, 'message' => 'Solicitud creada exitosamente', 'data' => $datos]);
			} else {
				$response = [
					'success' => false,
					'errors' => ['ERROR AL MOVER ARCHIVO INTENTELO MAS TARDE']
				];
				return response()->json($response, 200);
			}
		} catch (\Exception $e) {
			Log::warning('error en la  base de datos', ['error' => $e]);
			$response = [
				'success' => false,
				'errors' => [$e->getMessage()]
			];
			return response()->json($response);
		}
	}
}
