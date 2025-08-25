@extends('layouts.menu')

@section('dashboard')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.1/dist/bootstrap-table.min.css">

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
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">Tramites en Linea</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">Interior</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <p class="ml-3 ml-md-0 "><b style="color: #004fbf;text-transform: none;">
                                        Publicidad Exterior.
                                    </b></p>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <h2>Listado de solicitudes Radicadas de Avisos Comerciales</h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover table-sm" id="tableAvisos" data-toggle="table"
                    data-search="true" data-method="get" data-pagination="true" data-page-size="20"
                    data-toolbar="#divRefresh" data-url="{{ route('publicidad.avisos.getData') }}"
                    data-row-style="rowStyle">

                </table>
            </div>
        </div>
    </div>
      <script>
        let dataTable = $('#tableAvisos');
        let columnas = [

            {
                field: 'id',
                align: 'center',
                formatter: ID,
                title: '#'

            },
            {
                formatter: botones,
                title: 'Accion'
            },
            {
                field: 'radicado',
                align: 'center',
                title: 'Radicado'
            },
            {
                field: 'PersonaTip',
                align: 'center',
                title: 'Tipo de persona'

            },
            {
                field: 'PersonaDoc',
                añlign: 'center',
                title: 'N° Documento'

            },
            {
                field: 'solicitante',
                align: 'center',
                title: 'Solicitante',
                formatter: solicitante,
            },
            {
                field: 'tipo_publicidad',
                align: 'center',
                title: 'Tipo de publicidad'

            },
            {
                field: 'fecha_vencimiento',
                align: 'center',
                title: 'Vencimiento'
            },
            {
                field: 'estado_solicitud',
                align: 'center',
                title: 'Estado de la solicitud'
            },
            {
                field: 'novedad',
                align: 'center',
                title: 'Novedad'
            },

        ];

        initTable(dataTable, columnas, [], 20, true, true);

        function botones(value, row, index) {
            return [
                `<div class="btn-group" role="group" aria-label="Basic example">
               <a href="/tramites/avisos-comerciales/detalle/${row.id}" type="button" class="btn btn-sm btn-primary" title="Ver Detalle">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><!-- Icon from Solar by 480 Design - https://creativecommons.org/licenses/by/4.0/ --><g fill="none" stroke="#ffffff" stroke-width="1.5"><path d="M3.275 15.296C2.425 14.192 2 13.639 2 12c0-1.64.425-2.191 1.275-3.296C4.972 6.5 7.818 4 12 4s7.028 2.5 8.725 4.704C21.575 9.81 22 10.361 22 12c0 1.64-.425 2.191-1.275 3.296C19.028 17.5 16.182 20 12 20s-7.028-2.5-8.725-4.704Z"/><path d="M15 12a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z"/></g></svg>
               </a>
            </div>`
            ].join('');
        }

        function solicitante(value, row, index) {
            let solicitante = ''
            if(row.PersonaTip == 'Natural'){
                solicitante = `${row.PersonaNombre} ${row.PersonaApe}`
            }else{
                solicitante = row.PersonaRazon
            }
            return [`${solicitante} `].join('');
        }






        function ID(value, row, index) {
            return index + 1;
        }

     function initTable(dataTable, columnas, data, size = 5, search = true, pagination = true) {
            dataTable.bootstrapTable('destroy').bootstrapTable({
                data: data,
                columns: columnas,
                search: search,
                pagination: pagination,
                sortable: true,
                pageSize: size,
                formatLoadingMessage: function() {
                    return 'Cargando datos...';
                }
            });
        }
    </script>

@endsection
@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.1/dist/bootstrap-table.min.js"></script>
@endpush
