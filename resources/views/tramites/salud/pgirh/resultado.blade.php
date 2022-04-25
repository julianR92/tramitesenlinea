@extends('layouts.menu')

@section('dashboard')
<style>
    th.sorting_desc::after, th.sorting_asc::after{
      right: -16px!important;
   }
</style>

    <div class="container mt-3 mb-4 m-xs-x-3 pgirh">
        <div class="row pl-4">
            <div class="px-0 col-md-9">
                <nav aria-label="Miga de pan" style="max-height: 20px;">
                    <ol class="breadcrumb" style="background-color: #FFFFFF;">
                        <li class="breadcrumb-item ml-3 ml-md-0">
                            <a style="color: #004fbf;" class="breadcrumb-text" href="https://www.gov.co/home/">Inicio</a>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">Tramites y servicios</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <p class="ml-3 ml-md-0 "><b style="color: #004fbf;text-transform: none;">
                                    Salud y Ambiente
                                    
                                    </b></p>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-12 pt-4">
            <h1 class="headline-xl-govco">Empresas PGIRH</h1>
            <div class="row pt-5">
                <div class="col-md-12 justify-content-center">

                    <div class="card govco-card animate__animated animate__bounceInRight">
                        <div class="card-header govco-card-header">
                            <span class="govco-icon govco-icon-table-p size-2x pr-3"> </span>
                         Registros
                        </div>

                        <div class="col-md-12 justify-content-center pt-4">
                            <div id="container_table" class="table-pagination-govco">

                                <table id="DataTables_Table_0"
                                    class="table display table-responsive-sm table-responsive-md tablas-pgirh" width="100%"
                                    style="text-align: left!important;">
                                    <thead>
                                        <tr>
                                            <th class="d-none">id</th>
                                            <th style="color: #004884;" class="">Razon Social</th>
                                            <th style="color: #004884;">Nit</th>                                          
                                            <th style="color: #004884;">Representante Legal</th>
                                            <th style="color: #004884;">Correo - Telefono</th>
                                            <th style="color: #004884;">Actividad Economica</th>
                                            <th style="color: #004884;">Fecha de Registro</th>
                                            <th style="color: #004884;">Acciones</th>

                                        </tr>
                                    </thead>
                                    @foreach ($datos_empresa as $solicitud)
                                    <tr>
                                        <td class="d-none">{{$solicitud->id}}</td>
                                       <td>{{$solicitud->razon_social}}</td>
                                       <td>{{$solicitud->nit}}</td>
                                        <td>{{$solicitud->representante_legal}}</td>
                                       <td>{{$solicitud->correo_electronico}} {{$solicitud->telefono}}</td>
                                       <td>{{$solicitud->descripcion}}</td>
                                       <td>{{$solicitud->created_at}}</td>
                                       <td>
                                        <div class="btn-group" role="group">
                                            <a type="button" class="btn-symbolic-govco align-column-govco" href="{{route('salud.pgirh.reportes', $solicitud->id)}}">
                                                <span class="govco-icon govco-icon-link-n"></span>
                                                <span class="btn-govco-text">Ver Reportes</span></a>
                                        </div>
                                       </td>

                                    </tr>
                                        
                                    @endforeach


                                    </tbody>

                                </table>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <a class="btn btn-round btn-high" href="{{ URL::route('salud.index') }}" style="float: left;">Volver</a>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>

    </div>     

@endsection


