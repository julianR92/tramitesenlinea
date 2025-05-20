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
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">Trámites en Línea</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <a style="color: #004fbf;" class="breadcrumb-text" href="#">
                                    Interior</a>
                            </div>
                        </li>
                        <li class="breadcrumb-item ">
                            <div class="image-icon">
                                <span class="breadcrumb govco-icon govco-icon-shortr-arrow" style="height: 22px;"></span>
                                <p class="ml-3 ml-md-0 ">
                                    <b style="color: #004fbf;text-transform: none;">
                                        Publicidad Exterior
                                    </b>
                                </p>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-12 pt-4" style="padding-left: 10px!important">
            <h1 class="headline-xl-govco">Administración Solicitud</h1>
            <div class="row pt-3">
                <div class="table-simple-headblue-govco col-md-12 animate__animated animate__bounceInRight">
                    <table class="table table-responsive-md table-responsive-md">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    Solicitud N° - {{ $solicitud->radicado }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Radicado N°&nbsp;<br></strong>{{ $solicitud->radicado }}
                                </td>
                                <td>
                                    <strong>Tipo doc. del solicitante:</strong><br>
                                    {{ $persona->PersonaTipDoc }}
                                </td>
                                <td><strong>Número de documento:</strong><br>
                                    {{ $persona->PersonaDoc }}
                                </td>
                            </tr>
                            <tr>
                                @if ($persona->PersonaTipo == 'Juridica')
                                    <td><strong>Razón social:</strong><br>
                                        {{ $persona->PersonaRazon }}
                                    </td>
                                @else
                                    <td><strong>Nombre del solicitante:</strong><br>
                                        {{ $persona->PersonaNombre }} {{ $persona->PersonaApe }}
                                    </td>
                                @endif
                                <td><strong>Teléfono/celular:</strong><br>
                                    {{ $persona->PersonaTel }}
                                </td>
                                <td><strong>Correo eletrónico:</strong><br>
                                    {{ $persona->PersonaMail }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Modalidad de la publicidad</strong><br>
                                    {{ $solicitud->modalidad }}
                                </td>
                                @if ($solicitud->modalidad == 'vallas')
                                    <td><strong>Tipo de valla</strong>
                                        <br>{{ $detalle->tipo_valla }}
                                    </td>
                                @endif
                                <td><strong>Tipo de publicidad</strong>
                                    <br>{{ $solicitud->tipo_publicidad }}
                                </td>
                            </tr>

                            <tr>
                                <td><strong>Alto de la publicidad</strong><br>
                                    {{ $detalle->alto_elemento }} (mts2)
                                </td>
                                <td><strong>Ancho de la publicidad</strong><br>
                                    {{ $detalle->ancho_elemento }} (mts2)
                                </td>
                                <td><strong>No de caras</strong><br>
                                    {{ $detalle->numero_caras }}
                                </td>

                            </tr>
                            <tr>
                                <td><strong>Área total (mts2)</strong><br>
                                    {{ $detalle->area_total_elemento }}
                                </td>
                                <td><strong>Número de elementos</strong><br>
                                    {{ $solicitud->numero_elementos }}
                                </td>
                                <td><strong>Ubicación del aviso</strong><br>
                                    {{ $detalle->direccion_elemento }}
                                </td>
                            </tr>


                            <tr style="background-color:#004884">
                                <td colspan="3" style="background-color:#004884; color:white">Administración del Trámite
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Estado de la solicitud:</strong><br>
                                    <p style="color: #069169;font-weight:bold">{{ $solicitud->estado_solicitud }} <span
                                            class="govco-icon govco-icon-check-p size-1x"></span></p>
                                    </p>
                                </td>
                                <td colspan="2"><strong>Fecha y hora de la solicitud</strong><br>
                                    {{ $solicitud->created_at }}
                                </td>

                            </tr>

                            <!-- Documentos -->
                            @include('tramites.interior.publicidad.documentos')

                            <!-- Novedades -->
                            @include('tramites.interior.publicidad.novedades')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLiq" role="dialog" aria-labelledby="modalLiqLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLiqLabel">Detalle de la liquidación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $fecha_inicial = date_create($solicitud->fecha_inicio);
                        $fecha_final = date_create($solicitud->fecha_fin);
                        $diferencia = date_diff($fecha_inicial, $fecha_final);
                        $dias = $diferencia->days;
                        $salario = $salario_minimo;

                        $dias = $solicitud->dias_restantes;
                        // $mes = $resultado->format('%m');
                        $valor_m2 = round(($salario * 4) / 48, 2);
                        $numero_m2 = str_replace(',', '.', $valor_m2);
                        $metro_formateado = number_format($numero_m2, 2, ',', '.');
                        $area = $detalle->area_total_elemento;
                        // _______________________________________________________
                        $valor_mensual = round(($area * $valor_m2) / 12, 2);
                        $numero_mensual = str_replace(',', '.', $valor_mensual);
                        $mensual_formateado = number_format($numero_mensual, 2, ',', '.');
                        $tipo_liquidacion = '';
                        $valor_total = 0;
                        $valor_diario = round($valor_mensual / 30, 2);

                        $mes = ceil($dias / 30);

                        if ($dias > 30) {
                            $tipo_liquidacion = 'PERMANENTE';
                        } else {
                            $tipo_liquidacion = 'TRANSITORIA';
                        }
                    @endphp
                    <div class="row form-group">
                        <input type="hidden" value="{{ $detalle->publicidad_id }}" class="form-control" name="SolicitudId"
                            readonly id="publicidad_id_modal">

                        <div class="col-md-3">
                            <label for="tipo_liquidacion" class="form-label">Tipo de liquidación</label>
                            <input type="text" value="{{ $tipo_liquidacion }}" class="form-control"
                                name="tipo_liquidacion" readonly id="tipo_liquidacion_modal">
                        </div>

                        <div class="col-md-3">
                            <label for="salario_minimo" class="form-label">Salario minimo</label>
                            <input type="text" value="${{ number_format($salario, 0, ',', '.') }}" class="form-control"
                                name="salario_minimo" readonly id="salario_minimo_modal">
                        </div>
                        <div class="col-md-3">
                            <label for="area_publicidad" class="form-label">Área mt2</label>
                            <input type="number" value="{{ $area }}" class="form-control" name="area_publicidad"
                                id="area_publicidad_modal" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="dia_publicidad" class="form-label">Días de la publicidad </label>
                            <input type="text" value="{{ $solicitud->dias_restantes }}" class="form-control"
                                name="dia_publicidad" id="dia_publicidad_modal" required readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="valor_m2" class="form-label">Valor M2</label>
                            <input type="" value="${{ $metro_formateado }}" class="form-control"
                                id="valor_m2_modal" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="valor_mensual" class="form-label">Valor mensual</label>
                            <input type="text" value="${{ $mensual_formateado }}" data-dia="{{ $valor_diario }}"
                                class="form-control" name="valor_mensual" id="valor_mensual_modal" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="meses_pautar" class="form-label">Dias a pautar</label>
                            <input type="number" value="0" class="form-control" name="meses_pautar"
                                id="meses_pautar_modal" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="valor_total_mostrar" class="form-label">Valor total</label>
                            <input type="text" value="${{ $valor_total }}" class="form-control"
                                name="valor_total_mostrar" id="valor_total_mostrar_modal" required readonly>

                            <input type="hidden" value="{{ $valor_total }}" class="form-control" name="valor_total"
                                id="valor_total_modal" required readonly>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="fecha_inicial_mostrar" class="form-label">Fecha Inicial</label>
                            <input type="date" value="" class="form-control" name="fecha_inicial_mostrar"
                                id="fecha_inicial_mostrar" min="{{ $solicitud->fecha_inicio }}"
                                max="{{ $solicitud->fecha_fin }}">
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="fecha_fin_mostrar" class="form-label">Fecha Final</label>
                            <input type="date" value="" class="form-control" name="fecha_fin_mostrar"
                                id="fecha_fin_mostrar" min="{{ $solicitud->fecha_inicio }}"
                                max="{{ $solicitud->fecha_fin }}">
                        </div>

                        <div class="col-md-3">
                            <label for="fecha_limite_mostrar" class="form-label d-none">Fecha límite</label>
                            <input type="date" class="form-control d-none" name="fecha_limite_mostrar"
                                id="fecha_limite_mostrar_modal" value="{{ $fecha_limite }}" readonly required>

                            <input type="hidden" value="{{ $fecha_limite }}" class="form-control" name="fecha_limite"
                                id="fecha_limite_modal" value="{{ $fecha_limite }}" required readonly>
                        </div>
                        {{-- <div class="col-md-5 pt-4">
                     <label for="documento_respuesta">Resolución:</label>
                     <a href="downoadPdf/{{$detalle->publicidad_id}}" target="_blank">Descargar</a>
                  </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="cargarValores();">Aceptar y Generar</button>
                        {{-- <button type="submit" class="btn btn-primary">Guardar</button> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('reportes')
    <script>
        var valor_total_parcial = 0;


        function cargarLiquidacion() {
            let consecutivo = document.getElementById('consecutivo').value;
            let fecha_consecutivo = document.getElementById('fecha_consecutivo').value;
            document.getElementById('fecha_inicial_mostrar').value = '';
            document.getElementById('fecha_fin_mostrar').value = '';
            document.getElementById('meses_pautar_modal').value = 0;
            document.getElementById('valor_total_mostrar_modal').value = 0;
            if (consecutivo == '') {
                alert('por favor ingrese un consecutivo');
                return
            } else if (fecha_consecutivo == '') {
                alert('por favor ingrese una fecha de consecutivo');
                return;
            } else {
                $('#modalLiq').modal('show');
            }

        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fecha_inicial_mostrar').addEventListener('change', calcularDiferenciaFechas);
            document.getElementById('fecha_fin_mostrar').addEventListener('change', calcularDiferenciaFechas);


        });

        function calcularDias() {
            let fecha_inicio = document.getElementById('fecha_inicio').value;
            let fecha_fin = document.getElementById('fecha_fin').value;
            if (fecha_inicio == '' || fecha_fin == '') {
                return;
            }
            let fecha_inicial = new Date(fecha_inicio);
            let fecha_final = new Date(fecha_fin);
            let resultado = fecha_final - fecha_inicial;
            let dias = resultado / (1000 * 60 * 60 * 24);
            dias = dias + 1;
            document.getElementById('dif_dias_publicidad').value = dias;
            document.getElementById('dif_dias_publicidad').value = dias;
        }

        function calcularDiferenciaFechas() {
         console.log('entro');
            const fechaInicialInput = document.getElementById('fecha_inicial_mostrar');
            const fechaFinalInput = document.getElementById('fecha_fin_mostrar');
            const resultadoDias = document.getElementById('resultado_dias');

            const fechaInicial = new Date(fechaInicialInput.value);
            const fechaFinal = new Date(fechaFinalInput.value);

            if (!fechaInicialInput.value || !fechaFinalInput.value) {
                alert("Por favor selecciona ambas fechas.");
                return;
            }

            if (fechaInicial > fechaFinal) {
                alert("fecha inicial no puede ser mayor que la fecha final.");
                fechaInicialInput.value = '';
                fechaFinalInput.value = '';
                document.getElementById('meses_pautar_modal').value = 0;
                document.getElementById('valor_total_mostrar_modal').value = 0;
                return;
            }

            const diferenciaTiempo = fechaFinal - fechaInicial; // Diferencia en milisegundos
            const diferenciaDias = diferenciaTiempo / (1000 * 60 * 60 * 24) + 1; // Convertir a días
            // Mostrar el resultado
            // resultadoDias.textContent = `Diferencia en días: ${diferenciaDias} día(s).`;
            document.getElementById('meses_pautar_modal').value = parseInt(diferenciaDias);
            calcularValorTotal(diferenciaDias);

        }

        function calcularValorTotal(dias = 0) {
            let valor_diario = document.getElementById('valor_mensual_modal').dataset.dia;
            let valor_total = parseFloat(valor_diario) * parseInt(dias);
            valor_total_parcial = valor_total
            let valor_formateado = formatearPesoColombiano(valor_total);
            document.getElementById('valor_total_mostrar_modal').value = valor_formateado;
        }

        function formatearPesoColombiano(numero) {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP'
            }).format(numero);
        }

        function cargarValores() {
            let valor_total_form = valor_total_parcial;
            let fecha_inicial_parcial = document.getElementById('fecha_inicial_mostrar').value;
            let fecha_final_parcial = document.getElementById('fecha_fin_mostrar').value;
            let dias_pautar = document.getElementById('meses_pautar_modal').value;
            if (fecha_inicial_parcial == '' || fecha_final_parcial == '') {
                alert('por favor ingrese ambas fechas');
                return;
            }
            // Crear un objeto con los valores
            const datos = {
                valor_total_form,
                fecha_inicial_parcial,
                fecha_final_parcial,
                dias_pautar
            };
            procesarDatos(datos);
        }

        function procesarDatos(data){
          document.getElementById('fecha_parcial_inicial').value = data.fecha_inicial_parcial|| '';
          document.getElementById('fecha_parcial_final').value = data.fecha_final_parcial|| '';
          document.getElementById('meses_pautar').value = data.dias_pautar|| '';
          document.getElementById('valor_total').value = data.valor_total_form|| '';
          descargarPdf(data);
          $('#modalLiq').modal('hide');
        }
        function descargarPdf(data) {
         let publicidad_id = doc.getElementById('publicidad_id').value;
                let consecutivo = doc.getElementById('consecutivo').value;
                let fecha_consecutivo = doc.getElementById('fecha_consecutivo').value;

                $(doc).on({
                    ajaxStart: function() {
                        $("#loadMe").modal({
                            backdrop: "static",
                            keyboard: false,
                            show: true,
                        });
                    },
                    ajaxStop: function() {
                        setInterval(() => {
                            $('#loadMe').modal('hide');
                        }, 1000);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "/tramites/interior/publicidad/detalle/downoadPdf/" + publicidad_id + "/" +
                        consecutivo + "/" + fecha_consecutivo+"/"+data.valor_total_form+"/"+data.fecha_inicial_parcial+"/"+data.fecha_final_parcial,
                    xhrFields: {
                        responseType: 'blob' // Esto es crucial para obtener el PDF como un blob
                    },

                    success: function(response) {
                        const url = window.URL.createObjectURL(response);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'liquidacion.pdf'; // Nombre del archivo que se descargará
                        link.click(); // Dispara el clic para descargar el archivo
                        window.URL.revokeObjectURL(url);
                    },
                    error: function() {
                        alert("error de petición ajax");
                    },
                }).done(function() {
                    console.log('done')
                    $('#loadMe').modal('hide');
                })
        }

        const doc = document;

        doc.addEventListener('click', (e) => {
            if (e.target.matches('#descargarPdf')) {
                let publicidad_id = doc.getElementById('publicidad_id').value;
                let consecutivo = doc.getElementById('consecutivo').value;
                let fecha_consecutivo = doc.getElementById('fecha_consecutivo').value;

                $(doc).on({
                    ajaxStart: function() {
                        $("#loadMe").modal({
                            backdrop: "static",
                            keyboard: false,
                            show: true,
                        });
                    },
                    ajaxStop: function() {
                        setInterval(() => {
                            $('#loadMe').modal('hide');
                        }, 1000);
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "/tramites/interior/publicidad/detalle/downoadPdf/" + publicidad_id + "/" +
                        consecutivo + "/" + fecha_consecutivo,
                    xhrFields: {
                        responseType: 'blob' // Esto es crucial para obtener el PDF como un blob
                    },

                    success: function(response) {
                        const url = window.URL.createObjectURL(response);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'liquidacion.pdf'; // Nombre del archivo que se descargará
                        link.click(); // Dispara el clic para descargar el archivo
                        window.URL.revokeObjectURL(url);
                    },
                    error: function() {
                        alert("error de petición ajax");
                    },
                }).done(function() {
                    console.log('done')
                    $('#loadMe').modal('hide');
                })
            }
        });
        let myForm = doc.getElementById('myForm1');
        myForm.addEventListener('submit', (e) => {
         let f_inicial = document.getElementById('fecha_inicial_mostrar').value.trim();
         let f_final = document.getElementById('fecha_fin_mostrar').value.trim();
         let v_total = document.getElementById('meses_pautar_modal').value.trim();
         let v_dias = document.getElementById('valor_total_mostrar_modal').value.trim();
         if (f_inicial === '') {
                alert('Por favor, revise la liquidacion. , falta la fecha inicial');
                event.preventDefault(); // Detener el envío del formulario
                return;
            }

            if (f_final === '') {
                alert('Por favor, revise la liquidacion. falta la fecha final');
                event.preventDefault(); // Detener el envío del formulario
                return;
            }
         if (v_total === '') {
                alert('Por favor, revise la liquidacion. falta calculara el valor total');
                event.preventDefault(); // Detener el envío del formulario
                return;
            }

            if (v_dias === '') {
                alert('Por favor, revise la liquidacion., falta calcular los dias ');
                event.preventDefault(); // Detener el envío del formulario
                return;
            }
        })
    </script>
@endpush
