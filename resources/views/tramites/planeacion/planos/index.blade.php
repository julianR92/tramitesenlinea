@extends('layouts.menu')

@section('dashboard')
<style>
   .container {
      max-width: 1350px;
   }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
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
      <h2>Listado de solicitudes radicadas</h2>
   </div>
	<div class="row mb-0">
		<div class="col-md-12 mb-0">
				<strong class="badge badge-primary">En trámite:</strong> {{ $conteos['En trámite'] }} |
				<strong class="badge badge-primary">Para entrega:</strong> {{ $conteos['Para entrega'] }} |
				<strong class="badge badge-primary">Rechazado:</strong> {{ $conteos['Rechazado'] }} |
				<strong class="badge badge-primary">Finalizado:</strong> {{ $conteos['Finalizado'] }}
		</div>
	</div>
   <div class="row">
      <div class="col-md-12">
         <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="table_planos">
               <thead>
                  <tr>
                     <th data-sortable="true">Radicado</th>
                     <th data-sortable="true">Fecha solicitud</th>
                     <th data-sortable="true" data-formatter="validarCol" data-field="estado_solicitud">Estado solicitud</th>
                     <th data-sortable="true">Documento</th>
                     <th data-sortable="true">Nombre solicitante</th>
                     {{-- <th>Teléfono</th> --}}
                     <th data-sortable="true">No. predial</th>
                     <th data-sortable="true">Dirección del predio</th>
                     <th data-sortable="true">Fecha aprox. documentación</th>
                     <th data-align="center">Adj.</th>
                     <th data-align="center">Acciones</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($datos as $item)
                  <tr>
                     <td class="small">{{ $item->radicado }}</td>
                     <td class="small">{{ $item->fecha_solicitud }}</td>
                     <td>{{ $item->estado_solicitud }}</td>
                     <td class="small">{{ $item->documento_identificacion }}</td>
                     <td class="small">{{ $item->nombre_solicitante}}</td>
                     {{-- <td class="small">{{ $item->telefono }}</td> --}}
                     <td class="small">{{ $item->numero_predial }}</td>
                     <td class="small">{{ $item->direccion_predio }}</td>
                     <td class="small">{{ $item->fecha_aproximada_documentacion }}</td>
                     <td class="small text-center align-middle" data-align="center">
                        <a href="{{ asset($item->documento_certificado_libertad) }}" target="_blank"><i
                              class="fas fa-lg fa-file-pdf text-danger"></i></a>

                     </td>
                     <td class="small text-center align-middle">
                        <a href="{{ route('tramites.planeacion.prestamo-planos.detail', $item->id) }}"
                           title="Ver solicitud">
                           <i class="fas fa-info-circle fa-lg text-info"></i>
                        </a>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
@endsection
@push('reportes')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>
<script>
   $('#table_planos').bootstrapTable({
      pagination: true,
      search: true,
      showColumns: false,

      formatNoMatches: function () {
         return 'No se encontraron registros';
      },
      formatSearch: function () {
         return 'Buscar...';
      },

   });

   function validarCol(value, row, index, field) {
      if (field == 'estado_solicitud') {
         return `
         <span class="${row.estado_solicitud == 'Finalizado' ? 'badge badge-success' : ''}">
            ${row.estado_solicitud}
         `;
      }
   }
</script>
@endpush
