@extends('layouts.menu')

@section('dashboard')

    <div class="container mt-3 mb-4 m-xs-x-3">

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
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">Tramites en Linea</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <p class="ml-3 ml-md-0 "><b style="color: #004fbf;text-transform: none;">
                                        Interior
                                    </b></p>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-12 pt-4" style="padding-left: 10px!important">
            <h1 class="headline-xl-govco">Tramites Secretaria de Interior</h1>
            <div class="row pt-5">
                  
               {{-- INICIO CARD  --}}
              <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="card govco-card animate__animated animate__flipInX" style="max-height: 500px!important">
                    <div class="card-header govco-card-header">
                        <h4>
                        Categorización de parqueaderos</h4> 
                    </div>
                    
                    <div class="card-body">
                        <p style="text-align: justify">El objetivo del tramite es clasificar y categorizar los parqueaderos públicos ubicados en el municipio de Bucaramanga, para su correcto funcionamiento. Inicia con la solicitud del ciudadano hasta la expedición de la resolución que otorga la categorización o modificación de categorización de los parqueaderos públicos ubicados en el municipio de Bucaramanga. </p>
                      </div>
                      <div class="card-footer govco-card-footer govco-center">
                        <a class="btn-low-mix-govco align-column-govco" href="{{route('interior.parqueaderos.index')}}">
                          <span class="btn-mix-govco__title">Ver mas detalles</span>
                          <span class="btn-mix-govco__icon govco-icon govco-icon-simpled-arrow"></span>
                        </a>
                      </div>                
                    </div>
                </div>
                {{-- </ FIN CARD --}}

                {{-- INICIO CARD  --}}
              <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="card govco-card animate__animated animate__flipInX" style="max-height: 500px!important">
                    <div class="card-header govco-card-header">
                        <h4>
                        Permisos para Espectáculos Públicos</h4> 
                    </div>
                    
                    <div class="card-body">
                        <p style="text-align: justify">El objetivo del tramite es Otorgar el permiso a eventos que involucren aglomeraciones de público complejas, las cuales se miden en variables como: aforo, tipo de evento, clasificación de edad, lugar donde se desarrolla, infraestructura a utilizar, entorno de lugar, caracteristicas del pulicos, frecuencia entre otros, lo cual genere una alta afectación de la dinamica normal del municipio.</p>
                      </div>
                      <div class="card-footer govco-card-footer govco-center">
                        <a class="btn-low-mix-govco align-column-govco" href="{{route('interior.eventos.index')}}">
                          <span class="btn-mix-govco__title">Ver mas detalles</span>
                          <span class="btn-mix-govco__icon govco-icon govco-icon-simpled-arrow"></span>
                        </a>
                      </div>                
                    </div>
                </div>
                {{-- </ FIN CARD --}}

                


            
            </div>
        </div>



    </div>
@endsection