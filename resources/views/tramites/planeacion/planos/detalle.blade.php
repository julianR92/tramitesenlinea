@extends('layouts.menu')

@section('dashboard')

<div class="container">
	<div class="row mb-4">
		<div class="px-0 col-md-9">
			<nav aria-label="Miga de pan" style="max-height: 20px;">
				<ol class="breadcrumb" style="background-color: #FFFFFF;">
					<li class="breadcrumb-item ml-3 ml-md-0">
						<a style="color: #004fbf;" class="breadcrumb-text" href="https://www.gov.co/home/">Inicio</a>
					</li>
					<li class="breadcrumb-item ">
						<div class="image-icon">
							<span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
							<a style="color: #004fbf;" class="breadcrumb-text" href="#">Trámites en Línea</a>
						</div>
					</li>
					<li class="breadcrumb-item ">
						<div class="image-icon">
							<span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
							<a style="color: #004fbf;" class="breadcrumb-text" href="#">Planeación</a>
						</div>
					</li>
					<li class="breadcrumb-item ">
						<div class="image-icon">
							<span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
							<p class="ml-3 ml-md-0 "><b style="color: #004fbf;text-transform: none;">
									Prestamo de planos
								</b></p>
						</div>
					</li>
				</ol>
			</nav>
		</div>
	</div>

	<div class="row">
		<h2>Detalle del radicado No. {{$datos->radicado}}</h2>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">Datos del solicitante</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<p class="card-text"><b>Documento:</b> {{$datos->documento_identificacion}}</p>
							<p class="card-text"><b>Dirección:</b> {{$datos->direccion_solicitante}}</p>
							<p class="card-text"><b>Correo:</b> {{$datos->correo_electronico}}</p>
						</div>
						<div class="col-md-6">
							<p class="card-text"><b>Nombre:</b> {{$datos->nombre_solicitante}}</p>
							<p class="card-text"><b>Teléfono:</b> {{$datos->telefono}}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="card-title mb-0">Datos de la solicitud</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<p class="card-text"><b>Fecha de la solicitud:</b> {{$datos->fecha_solicitud}}</p>
							<p class="card-text"><b>No. de licencia:</b> {{$datos->numero_licencia}}</p>
							<p class="card-text"><b>Tipo de licencia:</b> {{$datos->tipoLicencia->tipo_licencia??''}}</p>
							<p class="card-text"><b>Modalidad:</b> {{$datos->modalidadLicencia->modalidad??''}}</p>
							<p class="card-text"><b>No. Predial:</b> {{$datos->numero_predial}}</p>
							<p class="card-text"><b>Propietario:</b> {{$datos->propietario_predio}}</p>
							<p class="card-text"><b>Dirección del predio:</b> {{$datos->direccion_predio}}</p>
						</div>
						<div class="col-md-6">
							<p class="card-text"><b>Barrio:</b> {{$barrio->nombre}}</p>
							<p class="card-text"><b>Contructor:</b> {{$datos->nombre_constructor}}</p>
							<p class="card-text"><b>No. folio matrícula inmobiliraria:</b>
								{{$datos->numero_folio_matricula_inmobiliaria}}</p>
							<p class="card-text"><b>Fecha aprox. documentación:</b> {{$datos->fecha_aproximada_documentacion}}
							</p>
							<p class="card-text"><b>Documentos requeridos:</b> {{$datos->documentos_requeridos}}</p>
							<p class="card-text"><b>Motivo de la destinación:</b> {{$datos->motivo_destinacion}}</p>
							<p class="card-text"><b>Adjunto:</b> <a href="{{ asset($datos->documento_certificado_libertad) }}"
									target="_blank"><i class="fas fa-lg fa-file-pdf text-danger"></i></a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="d-flex justify-content-between">
						<h5 class="card-title mb-0">Acciones a realizar</h5>
						<h6 class="card-title mb-0">Estado de la solicitud: <span class="text-success"
								style="text-shadow: #FC0 1px 0 10px;">{{$datos->estado_solicitud}}</span></h6>
					</div>
				</div>
				<div class="card-body">
					@if($datos->estado_solicitud == 'Finalizado')
					<div class="row">
						<div class="col-md-12 text-center">
							<h4>La solicitud se encuentra finalizada, no hay más acciones para realizar.</h4>
							<a href="{{ route('planeacion.prestamo-planos.index') }}" class="btn btn-info">Ir al listado de
								solicitudes</a>
						</div>
					</div>
					@else
					<form action="{{ route('tramites.planeacion.prestamo-planos.update') }}" method="POST" id="frm">
						<div class="row">
							<div class="col-md-6">
								@csrf
								<input type="hidden" name="id" id="id" value="{{$datos->id}}">
								<div class="form-group">
									<label for="estado_solicitud">
										Estado de la solicitud
									</label>
									<select class="form-control" name="estado_solicitud" id="estado_solicitud" required>
										<option value="" selected>Seleccione...</option>
										<option value="En trámite">En trámite</option>
										<option value="Para entrega">Para entrega</option>
										<option value="Rechazado">Rechazado</option>
										<option value="Finalizado">Finalizado</option>
									</select>
								</div>
								<div class="form-group">
									<label for="planos_observaciones">Observaciones</label>
									<textarea class="form-control" id="planos_observaciones" name="planos_observaciones"
										rows="3"></textarea>
								</div>
							</div>
							<div class="col-md-6 d-flex flex-column">
								<div class="mt-auto mb-3">
									<button type="submit" class="btn btn-primary">Enviar</button>
								</div>
							</div>
						</div>
					</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('reportes')
<script>
	document.getElementById('frm').addEventListener('submit', function (event) {
      const estado = document.getElementById('estado_solicitud').value;

      if (estado === 'Finalizado') {
         event.preventDefault(); // Detiene el envío del formulario

         Swal.fire({
            title: '¿Desea finalizar la solicitud?',
            text: 'Una vez finalizada, no podrá modificarla.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'No',
         }).then((result) => {
            if (result.isConfirmed) {
               this.submit();
            } else {
               return;
            }
         });
      }
   });
</script>
@endpush