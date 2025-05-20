@extends('layouts.app')

@section('title', 'Liquidacion Oficial de Estampillas')
@section('content')

    <style>
        .clockpicker-button {
            background-color: #3366CC !important;
            color: white !important;
        }

        /** SPINNER CREATION **/

        .loader {
            position: relative;
            text-align: center;
            margin: 15px auto 35px auto;
            z-index: 9999;
            display: block;
            width: 80px;
            height: 80px;
            border: 10px solid rgba(0, 0, 0, .3);
            border-radius: 50%;
            border-top-color: #004884;
            animation: spin 1s ease-in-out infinite;
            -webkit-animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            to {
                -webkit-transform: rotate(360deg);
            }
        }

    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="container mt-3 mb-4 m-xs-x-3">
    <div class="row pl-4">
            <div class="px-0 col-md-9 col-xs-12 col-sm-12">
                <nav aria-label="Miga de pan" style="max-height: 20px;">
                    <ol class="breadcrumb" style="background-color: #FFFFFF;">
                        <li class="breadcrumb-item ml-3 ml-md-0">
                            <a style="color: #004fbf; font-size:14px;" class="breadcrumb-text" href="https://www.bucaramanga.gov.co" tabindex="3">Inicio</a>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <a style="color: #004fbf; font-size:14px;" class="breadcrumb-text" href="https://www.bucaramanga.gov.co/tramites/" tabindex="4">Tramites y servicios</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <p class="ml-3 ml-md-0 text-miga" style="color: #004884; font-size:14px;">
                                  Liquidacion Oficial de Estampillas 
                                    </p>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="container-fluid xxx">
            <div class="row mt-2">
                <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                     <div class="col-md-12" style="padding-left: 0!important">
                        <div class="card step-progress border-0" style="font-size: 10px; background-color: transparent !important;">
                            <div class="step-slider">
                                <!--<div data-id="step1" class="step-slider-item active" ><p style="padding-top: 0px;margin-top:5px;"></p></div>-->
                                <div data-id="step2" class="step-slider-item active">
                                    <p style="padding-top: 0px;margin-top:5px;color: #BABABA;" id="barra_progreso"><span
                                            class="circle_cero">1</span> Inicio</p>
                                </div>
                                <div data-id="step3" class="step-slider-item">
                                    <p style="padding-top: 0px;margin-top:5px; color: #3366CC; " id="barra_progreso"><span
                                            class="circle_uno">2 </span> Hago mi solicitud</p>
                                </div>
                                <div data-id="step4" class="step-slider-item">
                                    <p style="padding-top: 0px;margin-top:5px;" id="barra_progreso"><span
                                            class="circle_dos">3</span>Procesan mi solicitud</p>
                                </div>
                                <div data-id="step5" class="step-slider-item">
                                    <p style="padding-top: 0px;margin-top:5px;" id="barra_progreso"><span
                                            class="circle_dos">4</span> Respuesta</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <form action="" method="" role="form" id="myForm" enctype="multipart/form-data"
                        class="form-ciudadano-estampillas">
                        @csrf
                        <div class="card govco-card border-0 shadow-none" style="border-radius: 0px;">

                            <h1 class="headline-xl-govco">Pago en linea - Liquidacion Oficial de Estampillas Municipales</h1>

                            <div class="alert-primary-govco alert alert-dismissible fade show mt-3"
                                aria-label="Alerta informativa">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"
                                    title="Cerrar">&times;</button>
                                <div class="alert-heading">
                                    <span class="govco-icon govco-icon-bell-sound-p size-2x"></span>
                                    <span class="headline-l-govco">Importante</span>
                                </div>
                                <p style="text-align: justify"> Este trámite, la liquidación oficial de estampilla municipal para el bienestar del adulto mayor y estampilla municipal procultura,
                                     se debe realizar en la suscripción de actas de posesión de los empleados del Municipio de Bucaramanga, Entidades descentralizadas del Municipio,
                                      la Contraloría de Bucaramanga, Personería de Bucaramanga y el Concejo Municipal de Bucaramanga que sean o excedan los
                                       cinco (5) salarios mínimos legales vigentes mensuales. </p>
                            </div>
                        </div>

                        <h3 class="headline-l-govco mt-3 pl-0">1. Datos Generales de la Solicitud <br> <span class="small-text-govco">*Campos obligatorioss</span></h3>

                        <div id="top">
                            <div class="alert alert-danger print-error-msg alert-dismissible fade show" style="display:none"
                                role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <ul></ul>
                            </div>
                        </div>
                        <a href="#top" class="d-none" id="a-top"></a>



                        <div class="row form-group mt-2">
                            <!--<div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="tipo_persona" class="form-label">Tipo Persona * </label>
                                <select class="form-control  @error('tipo_persona') is-invalid @enderror"
                                    name="tipo_persona" id="tipo_persona_espec" required>
                                    <option value="">Seleccione</option>
                                    <option value="N">Natural</option>
                                    <option value="J">Jurídica</option>

                                </select>
                                @error('tipo_persona')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->

                            <div class="col-md-6 pl-1 pr-1 pt-3 caja_nombreslo ">
                                <label for="nom_solicitante" class="form-label">Nombres del Solicitante 
                                    * </label>
                                <input value="{{ old('nom_solicitante') }}" type="text"
                                    class="form-control name_validate  @error('nom_solicitante') is-invalid @enderror"
                                    name="nom_solicitante" id="nom_solicitante" maxlength="25" minlength="4"
                                    onkeypress="return Letras(event)" onkeyup="aMayusculas(this.value,this.id)">
                                @error('nom_solicitante')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3 caja_nombreslo ">
                                <label for="ape_solicitante" class="form-label">Apellidos del Solicitante y/o
                                    Responsable * </label>
                                <input value="{{ old('ape_solicitante') }}" type="text"
                                    class="form-control name_validate  @error('ape_solicitante') is-invalid @enderror"
                                    name="ape_solicitante" id="ape_solicitante" maxlength="25" minlength="4"
                                    onkeypress="return Letras(event)" onkeyup="aMayusculas(this.value,this.id)">
                                @error('ape_solicitante')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            



                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoTipDoc" class="form-label">Tipo de Documento * </label>
                                <select class="form-control  @error('LoTipDoc') is-invalid @enderror"
                                    name="LoTipDoc" id="LoTipDoc">
                                    <option value="">Seleccione</option>
                                    <option value="C.C.">Cedula de Ciudadanía</option>
                                    <!--<option value="NIT">NIT</option>-->
                                </select>
                                @error('LoTipDoc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoNumIde" class="form-label">Numero de Identificacion* </label>
                                <input value="{{ old('LoNumIde') }}" type="text"
                                    class="form-control document_validate  @error('LoNumIde') is-invalid @enderror"
                                    name="LoNumIde" id="LoNumIde" maxlength="15" minlength="4"
                                    onkeypress="return Numeros(event)" required>
                                @error('LoNumIde')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!--<div class="col-md-12 pl-1 pt-3">

                                <label for="direccion_notificacion" class="form-label">Dirección de notificación*
                                </label><button type="button" class="btn btn-link"><span
                                        style="text-transform: lowercase; font-size: 12px;" class="text-primary"
                                        data-toggle="modal" data-target="#ModalDirecciones">(Clic para insertar
                                        direccion)</span></button>
                                <input type="text" value="{{ old('direccion_notificacion') }}"
                                    class="form-control  @error('direccion_notificacion') is-invalid @enderror"
                                    name="direccion_notificacion" id="direccion_solicitante" maxlength="120" required
                                    readonly>
                                @error('direccion_notificacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="barrio_solicitante" class="form-label">Barrio* </label>
                                <select name="barrio_notificacion" id="barrio_solicitante"
                                    class="form-control @error('barrio_notificacion') is-invalid @enderror" required>
                                    <option value=""></option>
                                    @foreach ($Barrios as $barrio)
                                        <option value="{{ $barrio->nombre }}">{{ $barrio->nombre }}</option>

                                    @endforeach
                                </select>
                                @error('barrio_notificacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoTel" class="form-label">Teléfono / Celular * </label>
                                <input value="{{ old('LoTel') }}" type="text"
                                    class="form-control  @error('LoTel') is-invalid @enderror number_validate"
                                    name="LoTel" id="LoTel" maxlength="10" minlength="7"
                                    onkeypress="return Numeros(event)" required>
                                @error('LoTel')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoEmail" class="form-label">Correo Electronico de Notificacion*
                                </label>
                                <input value="{{ old('LoEmail') }}" type="mail"
                                    class="form-control  @error('LoEmail') is-invalid @enderror email_validate"
                                    name="LoEmail" id="LoEmail" onkeypress="return Email(event)" required>
                                @error('LoEmail')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="email_confirmado" class="form-label">Confirme su correo* </label>
                                <input type="mail" onpaste="return false;"
                                    class="form-control  @error('email_confirmado') is-invalid @enderror email_validate"
                                    name="email_confirmado" id="email_confirmado" onkeypress="return Email(event)" required onpaste="return false;">
                                @error('email_confirmado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoNumActAdm" class="form-label">Numero acto administrativo de Nombramiento* </label>
                                <input value="{{ old('LoNumActAdm') }}" type="text"
                                    class="form-control number_validate "
                                    name="LoNumActAdm" id="LoNumActAdm" 
                                    onkeypress="return Numeros(event)" required>
                                
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoFecAct" class="form-label">Fecha de Acto administrativo * </label>
                                <input type="date" class="form-control  @error('LoFecAct') is-invalid @enderror"
                                    name="LoFecAct" id="LoFecAct">
                                @error('LoFecAct')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            
                            

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoEnti" class="form-label">Entidad en la cual se posesiona* </label>
                                <select class="form-control  @error('LoEnti') is-invalid @enderror"
                                    name="LoEnti" id="LoEnti">
                                    <option value="">Seleccione</option>
                                    <option value="Alcaldia de Bucaramanga">Alcaldia de Bucaramanga</option>
                                    <option value="Bomberos de Bucaramanga">Bomberos de Bucaramanga</option>
                                    <option value="IMEBU">IMEBU</option>
                                    <option value="INDERBU">INDERBU</option>
                                    <option value="ICMT">ICMT</option>

                                    <!--<option value="NIT">NIT</option>-->
                                </select>
                                @error('LoEnti')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoCargo" class="form-label">Cargo* </label>
                                <input value="{{ old('LoCargo') }}" type="text"
                                    class="form-control  "
                                    name="LoCargo" id="LoCargo" maxlength="50" 
                                     required>
                                
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoCodigo" class="form-label">Codigo* </label>
                                <input value="{{ old('LoCodigo') }}" type="text"
                                    class="form-control  "
                                    name="LoCodigo" id="LoCodigo" maxlength="20" 
                                     required>
                                
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoGrado" class="form-label">Grado* </label>
                                <input value="{{ old('LoGrado') }}" type="text"
                                    class="form-control "
                                    name="LoGrado" id="LoGrado" maxlength="10" 
                                     required>
                                
                            </div>
                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoTipNom" class="form-label">Tipo de Nombramiento * </label>
                                <select class="form-control  @error('LoTipNom') is-invalid @enderror"
                                    name="LoTipNom" id="LoTipNom">
                                    <option value="">Seleccione</option>
                                    <option value="Posesion">Posesion</option>
                                    <option value="Encargo">Encargo</option>
                                    <option value="Comision">Comision</option>
                                </select>
                                @error('LoTipNom')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="LoValMen" class="form-label">Valor mensual a devengar*
                                     </label>
                                <input value="{{ old('LoValMen') }}" type="text"
                                    class="form-control  "
                                    name="LoValMen" id="LoValMen" maxlength="20" minlength="5"
                                    onkeypress="return Numeros(event)" required>
                                
                                <small style="font-size: 11px!important">Se constituye el Hecho Generador
                                         cuando el salario a devengar sea o exceda los cinco (5) salarios 
                                         mínimos legales vigentes mensuales</small>
                            </div>

                            

                           

                            <!--<div class="col-md-12 pl-1 pr-1 pt-3">
                                <label for="nombre_evento" class="form-label">Nombre del evento * </label>
                                <input value="{{ old('nombre_evento') }}" type="text"
                                    class="form-control razon_social  @error('nombre_evento') is-invalid @enderror"
                                    name="nombre_evento" id="nombre_evento" maxlength="240" minlength="4"
                                    onkeypress="return Letras(event)" onkeyup="aMayusculas(this.value,this.id)">
                                @error('nombre_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="id_evento" class="form-label">Tipo de evento * </label>
                                <select class="form-control  @error('id_evento') is-invalid @enderror" name="id_evento"
                                    id="id_evento" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">UNICO</option>
                                    <option value="2">SUCESIVO</option>
                                </select>
                                @error('id_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->

                            
                            <!--

                            <div class="col-md-6 pl-1 pr-1 pt-3 caja-fecha-fin d-none">
                                <label for="fecha_fin_evento" class="form-label">Fecha de final del evento * </label>
                                <input type="date" class="form-control  @error('fecha_fin_evento') is-invalid @enderror"
                                    name="fecha_fin" id="fecha_fin_evento">
                                @error('fecha_fin_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 pl-1 pr-1 pt-3">
                                <label for="hora_inicio" class="form-label">Hora de inicio* </label>
                                <div class="input-group clockpicker">
                                    <input type="text" value="{{ old('hora_inicio') }}"
                                        class="form-control @error('hora_inicio') is-invalid @enderror " value="00:00"
                                        name="hora_inicio" id="hora_inicio" required>
                                </div>
                                @error('hora_inicio')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3 pl-1 pr-1 pt-3">
                                <label for="hora_fin" class="form-label">Hora fin* </label>
                                <div class="input-group clockpicker">
                                    <input type="text" value="{{ old('hora_fin') }}"
                                        class="form-control @error('hora_fin') is-invalid @enderror " value="00:00"
                                        name="hora_fin" id="hora_fin" required>
                                </div>
                                @error('hora_fin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>




                            <div class="col-md-12 pl-1 pt-3">

                                <label for="lugar_evento" class="form-label">Ubicación del evento * </label><button
                                    type="button" class="btn btn-link"><span
                                        style="text-transform: lowercase; font-size: 12px;" class="text-primary"
                                        data-toggle="modal" data-target="#ModalDireccionesEmpresas">(Clic para insertar
                                        direccion)</span></button>
                                <input type="text" value="{{ old('lugar_evento') }}"
                                    class="form-control  @error('lugar_evento') is-invalid @enderror" name="direccion_evento"
                                    id="direccion_empresa" maxlength="120" required readonly>
                                @error('lugar_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="barrio_empresa" class="form-label">Barrio* </label>
                                <select name="barrio_evento" id="barrio_empresa"
                                    class="form-control @error('barrio_evento') is-invalid @enderror" required>
                                    <option value=""></option>
                                    @foreach ($Barrios as $barrio)
                                        <option value="{{ $barrio->nombre }}">{{ $barrio->nombre }}</option>

                                    @endforeach
                                </select>
                                @error('barrio_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12 pl-1 pt-3">

                                <label for="descripcion_evento">Descripcion del evento*</label>
                                <textarea name="descripcion_evento" id="descripcion_evento"
                                    onkeypress="return Observaciones(event)" onkeyup="aMayusculas(this.value,this.id)"
                                    maxlength="500" class="form-control  @error('descripcion_evento') is-invalid @enderror"
                                    cols="2" rows="3" required></textarea>
                                @error('descripcion_evento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-12 pl-1 pt-3">
                                <label for="agregar_boleteria pb-3">Agregar Total de Boleteria <button type="button"
                                        class="btn btn-round btn-sm btn-middle" style="padding:5px!important;"
                                        onclick="openModal()">Anexar</button></label>
                                <div class="table-simple-headblue-govco">
                                    <table class="table display table-responsive-sm table-responsive-md" style="width:100%"
                                        id="tablaBoleteria">
                                        <thead>
                                            <tr>
                                                <th>Tipo de Boleteria</th>
                                                <th>Valor</th>
                                                <th>N° de boletas Impresas</th>
                                                <th>Acción</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="tipo_garantia" class="form-label">Tipo de Garantia * </label>
                                <input value="{{ old('tipo_garantia') }}" type="text"
                                    class="form-control name_validate  @error('tipo_garantia') is-invalid @enderror"
                                    name="tipo_garantia" id="tipo_garantia" placeholder="Ejemplo: CHEQUE" maxlength="25"
                                    minlength="4" onkeypress="return Letras(event)"
                                    onkeyup="aMayusculas(this.value,this.id)" required>
                                @error('tipo_garantia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> -->






                            <h3 class="headline-l-govco mt-3 pl-0">2. Documentos Adjuntos de la Solicitud <br> <span class="small-text-govco">*Campos obligatorios</span></h3>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="archivo_acta_posesion" class="form-label">Acto administrativo de nombramiento* <br><small class="text-danger"
                                        style="font-size: 11px!important">Solo se permiten archivos .pdf con un tamaño
                                        máximo de 10MB</small> </label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input class=" @error('archivo_acta_posesion') is-invalid @enderror documentos_adjuntos"
                                            id="archivo_acta_posesion" accept="application/pdf" name="archivo_acta_posesion" type="file"
                                            data-overwrite-initial="true" required>
                                        @error('archivo_acta_posesion')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="archivo_id" class="form-label">Documento de identificacion* <br><small
                                        class="text-danger" style="font-size: 11px!important">Solo se permiten archivos
                                        .pdf con un tamaño máximo de 10MB</small> </label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input class=" @error('archivo_id') is-invalid @enderror documentos_adjuntos"
                                            id="archivo_id" accept="application/pdf" name="archivo_id" type="file"
                                            data-overwrite-initial="true" required>
                                        @error('archivo_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <!--
                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="archivo_boleteria" class="form-label">Certificacion Boleteria* <br> <small
                                        class="text-danger" style="font-size: 11px!important">Solo se permiten archivos
                                        .pdf con un tamaño máximo de 10MB</small> </label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input
                                            class=" @error('archivo_boleteria') is-invalid @enderror documentos_adjuntos"
                                            id="archivo_boleteria" accept="application/pdf" name="archivo_boleteria"
                                            type="file" data-overwrite-initial="true" required>
                                        @error('archivo_boleteria')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="archivo_copia_cedula" class="form-label">Copia de cedula de representante*
                                    <br> <small class="text-danger" style="font-size: 11px!important">Solo se permiten
                                        archivos .pdf con un tamaño máximo de 10MB</small></label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input
                                            class=" @error('archivo_copia_cedula') is-invalid @enderror documentos_adjuntos"
                                            id="archivo_copia_cedula" accept="application/pdf" name="archivo_copia_cedula"
                                            type="file" data-overwrite-initial="true" required>
                                        @error('archivo_copia_cedula')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="archivo_solicitud" class="form-label">Solicitud Firmada*
                                    <br> <small class="text-danger" style="font-size: 11px!important">Solo se permiten
                                        archivos .pdf con un tamaño máximo de 10MB</small></label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input
                                            class=" @error('archivo_solicitud') is-invalid @enderror documentos_adjuntos"
                                            id="archivo_solicitud" accept="application/pdf" name="archivo_solicitud"
                                            type="file" data-overwrite-initial="true" required>
                                        @error('archivo_solicitud')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-md-6 pl-1 pr-1 pt-3">
                                <label for="arch_otros" class="form-label">Certificacion salarial (opcional)
                                    <br> <small class="text-danger" style="font-size: 11px!important">Solo se permiten
                                        archivos .pdf con un tamaño máximo de 10MB</small></label>
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input class=" @error('arch_otros') is-invalid @enderror documentos_adjuntos"
                                            id="arch_otros" accept="application/pdf" name="arch_otros"
                                            type="file" data-overwrite-initial="true">
                                        @error('arch_otros')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- por definir cuarto documento --}}
                            <div class="col-md-12 pl-1 pt-3">
                                <h4 class="headline-m-govco">Aviso de privacidad y autorización tratamiento de datos
                                    personales</h4>

                                <a class="btn btn-low px-0"
                                    href="https://www.bucaramanga.gov.co/Inicio/autorizacion-de-tratamiento-de-datos-personales/"
                                    target="_blank">Autorizo el tratamiento de datos personales</a>
                                <label class="checkbox-govco d-inline">
                                    <input type="checkbox" id="AT00" name="tratamiento_datos" checked value="SI" />
                                    <label for="AT00"> </label>
                                </label><br>

                                <a class="btn btn-low px-0"
                                    href="https://www.bucaramanga.gov.co/wp-content/uploads/2018/12/Resolucion-340-Dic-26-2018-y-Politica.pdf"
                                    target="_blank">Acepto términos y condiciones</a>
                                <label class="checkbox-govco d-inline">
                                    <input type="checkbox" id="AT01" name="acepto_terminos" checked value="SI" />
                                    <label for="AT01"> </label>
                                </label>
                                <p class="text-justify">Confirmo que soy mayor de edad y con plena capacidad para
                                    diligenciar el presente formulario.
                                    Así mismo declaro que la información aquí suministrada corresponde a la verdad.
                                    Declaro que he leído, entiendo y acepto las políticas de tratamiento de los datos que
                                    suministro,
                                    de conformidad con la Ley 1581 de 2012 y demás normas concordantes
                                    <label class="checkbox-govco d-inline">
                                        <input type="checkbox" id="AT02" name="confirmo_mayorEdad" id="" checked
                                            value="SI" />
                                        <label for="AT02"> </label>
                                    </label>
                                </p>
                                <p class="text-justify">Para efectos de notificaciones y comunicaciones electrónicas, en materia tributaria, de acuerdo a lo establecido en el Decreto 0040 de 2022, Autorizo que sean notificados al siguiente correo: <span id="span-notificaciones"></span>
                                    <label class="checkbox-govco d-inline">
                                        <input type="checkbox" id="AT03" name="confirmo_notificaciones" checked
                                            value="SI" />
                                        <label for="AT03"> </label>
                                    </label>
                                </p>
                            </div>
                            <div class="col-md-11 pl-1 pr-1 pt-3">
                                <p>Acepto que la información aquí registrada sea compartida con otras entidades y/o
                                    terceros vinculados a la Alcaldía de Bucaramanga</p>
                                @error('compartir_informacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-check-inline">
                                    <label class="radiolist-govco radiobutton-govco">
                                        <input type="radio" name="compartir_informacion" id="rb_si" value="SI" required
                                            checked />
                                        <label for="rb_si">SI</label>
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="radiolist-govco radiobutton-govco">
                                        <input type="radio" name="compartir_informacion" id="rb_no" value="NO" />
                                        <label for="rb_no">NO</label>
                                    </label>
                                </div>

                            </div>

                            <div class="col-md-12  pl-1 pr-1 pt-3 text-left mt-4" style="padding-left: 0px!important">
                                <div class="g-recaptcha" data-sitekey="6LdzXDwcAAAAAOgw8LzMLMjgnI2spGFhuCoMYlGc"></div>
                                <button style="font-size:15px;" type="submit" class="btn btn-round btn-middle btn_enviar_solicitud" name="consultar" onclick="return confirm('¿Esta seguro de realizar esta solicitud ?')">Enviar Solicitud</button>
                                <button style="font-size:15px;" class="btn btn-round btn-middle btn_carga d-none"
                                    type="button" disabled><span class="spinner-grow spinner-grow-sm text-primary"
                                        role="status" aria-hidden="true"></span> Enviando...</button>
                            </div>
                        </div>
                    </form>
                </div>








                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <!---contenido de cajas -->
                   {{-- manual --}}

                   <div class="accordion accordion-govco mb-3" id="">
                     <div class="card mb-0">
                         <div class="card-header row no-gutters" id="">
                             <button class="btn-link row no-gutters" type="button">
                                 <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                    <a  type="button" href="{{asset('library/Manuales/INSTRUCTIVO PARA REALIZAR SOLICITUD DEL REGISTRO DE ESPECTACULOS PUBLICOS EN EL MUNICIPIO DE BUCARAMANGA.pdf')}}" target="_blank"><h4 class="headline-s-govco" tabindex="5">Manual de usuario</h4></a>
                                 </div>
                                 <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                     <div class="btn-icon-close">
                                         {{-- <span class="govco-icon govco-icon-shortu-arrow-n size-1x"></span> --}}
                                         {{-- <span class="govco-icon govco-icon-shortd-arrow-n size-1x"></span> --}}
                                     </div>
                                 </div>
                             </button>
                         </div>                        
                     </div>
                 </div>
                    

                    <div class="accordion accordion-govco" id="EjemploAccordion-2">
                        <div class="card mb-0">
                           <div class="card-header row no-gutters" id="headingUno">
                              <button class="btn-link row no-gutters collapsed" type="button" data-toggle="collapse"
                                  data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9"  tabindex="6">
                                      <h4 class="headline-s-govco">¿Tienes dudas?</h4>
                                  </div>
                                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                      <div class="btn-icon-close">
                                          {{-- <span class="govco-icon govco-icon-shortu-arrow-n size-1x"></span> --}}
                                          <span class="govco-icon govco-icon-shortd-arrow-n size-1x"></span>
                                      </div>
                                  </div>
                              </button>
                          </div>
                            <div id="collapse1" class="collapse mb-3" aria-labelledby="headingUno"
                                data-parent="#EjemploAccordion-2">
                                <div class="card-body bg-color-selago">
                                    <div class="container">
                                        <p class="form-inline my-0"><span class="govco-icon govco-icon-email"></span> <a
                                                style="color: #3366CC; font-size: 13px!important;" href="mailto:lcerquera@bucaramanga.gov.co"
                                                target="_blank" tabindex="7"> lcerquera@bucaramanga.gov.co</a></p>
                                        <p class="form-inline my-0"><span class="govco-icon govco-icon-call-center"></span><a
                                                style="color: #3366CC; font-size: 13px!important;" href="tel:0376337000"> (+57)7 633 70 00</a></p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion accordion-govco" id="acc4">
                        <div class="card">
                           <div class="card-header row no-gutters" id="c4">
                              <button class="btn-link row no-gutters collapsed" type="button" data-toggle="collapse"
                                  data-target="#coll4" aria-expanded="false" aria-controls="coll4" id="btn_colapse">
                                  <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9" tabindex="8">
                                      <h4 class="headline-s-govco">¿Como fue tu experiencia durante el proceso?</h4>
                                  </div>
                                  <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                      <div class="btn-icon-close">
                                          {{-- <span class="govco-icon govco-icon-shortu-arrow-n size-1x"></span> --}}
                                          <span class="govco-icon govco-icon-shortd-arrow-n size-1x"></span>
                                      </div>
                                  </div>
                              </button>
                          </div>
                            <div id="coll4" class="collapse" aria-labelledby="c4" data-parent="#acc4">
                                <div class="card-body bg-color-selago">
                                    <div class="row justify-content-center spacer no-gutters">
                                        <div class="col-3 pl-3 pt-2">
                                            <button type="button" id="btn-facil-global" class="btn-symbolic-govco align-column-govco btn-facil-global" value="FACIL" tabindex="8">
                                                <span class="govco-icon govco-icon-check-cn size-3x"></span>
                                                <span class="btn-govco-text">Facil</span>
                                            </button>
                                        </div>

                                        <div class="col-3 pl-3 pt-2">
                                            <button type="button" id="btn-dificil-global" class="btn-symbolic-govco align-column-govco btn-dificil-global"  value="DIFICIL" tabindex="8">
                                                <span class="govco-icon govco-icon-x-cn size-3x"></span>
                                                <span class="btn-govco-text">Dificil</span>
                                            </button>
                                        </div>
                                    </div>
                                    {{-- modulo tramites --}}
                                    <input id="modulo" type="hidden" class="form-control modulo" value="ESPECTACULOS PUBLICOS">


                                    <div class="container text-center">
                                        <button type="button" class="btn btn-round btn-middle btn-block"
                                            id="btn-sugerencias" data-toggle="tooltip" data-placement="right"
                                            title="Después de escribir tus sugerencias oprime FACIL o DIFICIL para enviarlas"
                                            style="" tabindex="8">Escribe
                                            tus sugerencias</button><br>
                                        <div id="Texto_sugerencias" style="display: none;">
                                            <p style="color:#3366CC;"> Gracias por compartir tu experiencia</p>
                                        </div>

                                        <div id="text-button" style="padding-bottom: 10px; display: none;">
                                            <label class="text-left small">Escribe tus comentarios</label>
                                            <textarea class="form-control pb-2" rows="5"
                                                placeholder="Queremos conocer tu experiencia, sugerencias y consejos"
                                                id="text-area" tabindex="8" maxlength="300" onkeypress="return Direccion(event)"
                                                onkeyup="aMayusculas(this.value,this.id)"></textarea>
                                                <button type="button" class="btn btn-round btn-middle btn-block enviar-sugerencias"
                                                   id="enviar-sugerencias" data-toggle="tooltip" data-placement="right"
                                                   title=""style="" tabindex="8">Envia tus sugerencias</button>
                                               
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row justify-content-center">
                        <a href="{{route('liqestampillas.index')}}"><button style="font-size:15px;" type="button" class="btn btn-round btn-middle" id="btn-back" tabindex="8">Volver al inicio del tramite</button></a>
                      </div>              
                   
                                      
                </div>
            </div>
        </div>



    </div>
    {{-- fin contenedor pricincipal --}}

    

    {{-- MODAL CONSULTAR SOLICITUD --}}

    <div id="ModalConsulta" class="modal fade center" role="dialog">
        <div class="modal-dialog modal-lg" style="max-width: 1000px!important;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background:#E5EEFB;">
                    <h4 class="modal-title">Consultar Solicitud</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('espectaculos.consulta') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row form-row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><br>
                                <div class="form-group">
                                    <label style="color:#111111;" class="input" for="DD01"
                                        style="font-family: 'Barlow', sans-serif;">Buscar Por </label>
                                    <select id="VD01" name="tipo_parametro" class="form-control input-md"
                                        title="Seleccione la opción para validar el documento" required="required">
                                        <option value="">Seleccione</option>
                                        <option value="LoRadicado">Numero de radicado</option>
                                        <option value="LoNumIde">N° de identificación Solicitante
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><br>
                                <div class="form-group">
                                    <label style="color:#111111;" class="input" for="DD01"
                                        style="font-family: 'Barlow', sans-serif;">Digite Numero </label>
                                    <input type="text" name="parametro" id="VD00" class="form-control input-md"
                                        title="Seleccione la opción para validar el documento" required="required"
                                        onkeypress="return Numeros(event)" onkeyup="aMayusculas(this.value,this.id)"
                                        maxlength="40" minlength="5">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

            <button type="submit" class="btn btn-round btn-middle btn-outline-info" id="Boton">Realizar Búsqueda</button>
                        <button type="button" class="btn btn-round btn-middle btn-outline-info"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    {{-- modal de carga --}}
    <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="loader"></div>
                    <div clas="loader-txt">
                        <p>Enviando solicitud <br><br><small>Por favor espere...
                                </small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let emailValidate = document.getElementById('email_confirmado');
        emailValidate.addEventListener('change',(e)=>{{ }
         document.getElementById('span-notificaciones').innerHTML = '';
         document.getElementById('span-notificaciones').innerHTML = `<mark><b>${e.target.value}</b></mark>`;
    
    })
    </script>
    
        

@endsection
