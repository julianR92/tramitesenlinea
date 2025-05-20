@if ($novedades)
    <tr style="background-color:#004884">
        <td colspan="3" style="background-color:#004884; color:white">Novedades</td>
    </tr>

    <tr>
        <th>Novedad</th>
        <th>Estado</th>
        <th>Observaciones</th>
    </tr>

    @foreach ($novedades as $novedad)
        <tr>
            <td>{{ $novedad->NovedadEstado }} </td>
            <td>{{ $novedad->NovedadTipo }} </td>
            <td>{{ $novedad->NovedadComentario }} | {{ $novedad->created_at }}</td>
        </tr>
    @endforeach
@endif

<tr>
    {{-- aqui va el form --}}
    @if ($solicitud->estado_solicitud != 'RECHAZADA' && $solicitud->estado_solicitud != 'APROBADA')
        <form method="POST" class="form-ciudadano" action="{{ route('interior.publicidad.update') }}"
            enctype="multipart/form-data" id="myForm1">
            @csrf
            <input type="hidden" name="SolicitudId" id="SolicitudId" value="{{ $solicitud->id }}">
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
            <input type="hidden" value="{{ $detalle->publicidad_id }}" class="form-control" name="SolicitudId" readonly
                id="publicidad_id">
            <input type="hidden" value="{{ $tipo_liquidacion }}" class="form-control" name="tipo_liquidacion" readonly
                id="tipo_liquidacion">
            <input type="hidden" value="{{ $salario_minimo }}" class="form-control" name="salario_minimo" readonly
                id="salario_minimo">
            <input type="hidden" value="{{ $area }}" class="form-control" name="area_publicidad"
                id="area_publicidad" required readonly>
            <input type="hidden" value="{{ $solicitud->dias_restantes }}" class="form-control" name="dia_publicidad"
                id="dia_publicidad" required readonly>
            <input type="hidden" value="{{ $valor_m2 }}" class="form-control" id="valor_m2" required readonly>
            <input type="hidden" value="{{ $valor_mensual }}" class="form-control" name="valor_mensual"
                id="valor_mensual" required readonly>
            <input type="hidden" class="form-control" name="meses_pautar"
                id="meses_pautar" required readonly>
            <input type="hidden" value="${{ number_format($valor_total, 2) }}" class="form-control"
                name="valor_total_mostrar" id="valor_total_mostrar" required readonly>
            <input type="hidden" value="" class="form-control" name="valor_total" id="valor_total"
                required readonly>
            <input type="hidden" class="form-control d-none" name="fecha_limite_mostrar" id="fecha_limite_mostrar"
                value="{{ $fecha_limite }}" readonly required>
            <input type="hidden" value="{{ $fecha_limite }}" class="form-control" name="fecha_limite"
                id="fecha_limite" value="{{ $fecha_limite }}" required readonly>
            <input type="hidden" value="" class="form-control" name="fecha_parcial_inicial"
                id="fecha_parcial_inicial" value=""  readonly>
            <input type="hidden" value="" class="form-control" name="fecha_parcial_final"
                id="fecha_parcial_final" value=""  readonly>

            @if ($solicitud->dependencia == 'HACIENDA' && $solicitud->estado_solicitud == 'PENDIENTE')
<tr>
    <td colspan="3">
        <h3>Pendiente por pago, una vez caiga el pago, cambiara automaticamente el estado</h3>
    </td>
</tr>
@else
@php
    $novedadesCollection = collect($novedades);
    $revisionDocumentos = $novedadesCollection->contains(function ($novedad) {
        return $novedad->NovedadTipo === 'Revision de documentos';
    });
@endphp
@if (Str::lower($solicitud->dependencia) == Str::lower(auth()->user()->secretaria) ||
        Str::lower(auth()->user()->secretaria) == 'tic')
    <tr>
        <td>
            <h3>Estado actual de la solicitud: </h3>
            <h4>{{ $config_novedades[0]->titulo_estado }}</h4>
            <div class="form-group">
                <label for="novedad">{{ $config_novedades[0]->novedad }} *</label>
                <select class="form-control  @error('novedad') is-invalid @enderror" name="novedad" id="novedad"
                    required>
                    <option value="">Seleccione una opción</option>
                    @foreach ($config_novedades as $novedad)
                        <option value="{{ $novedad->novedad_id }}">{{ $novedad->opcion }}
                    @endforeach
                </select>
                @error('novedad')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>

        <td colspan="2">
            <div class="form-group">
                <label for="observacion">Observaciones</label>
                <textarea name="observacion" id="observacion" maxlength="255"
                    class="form-control  @error('observacion') is-invalid @enderror" id="observacion" cols="2" rows="4"></textarea>
                @error('observacion')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>
    </tr>

    @if ($solicitud->dependencia == 'hacienda')
        <tr>
            <td>
                <label for="consecutivo">Consecutivo</label>
                <input type="text" name="consecutivo" id="consecutivo" class="form-control"
                    @if ($config_novedades[0]->finaliza == 1) required @endif>


            </td>
            <td>
                <label for="fecha_consecutivo">Fecha consecutivo</label>
                <input type="date" name="fecha_consecutivo" id="fecha_consecutivo" class="form-control"
                    @if ($config_novedades[0]->finaliza == 1) required @endif>
            </td>
            <td>
                {{-- <a href="#" type="button" class="btn btn-round btn-middle btn-outline-info " id="descargarPdf">Descargar
         resolución</a> --}}

                <button type="button" id="btnLiquidar" class="btn btn-round btn-middle btn-outline-info"
                    onclick="cargarLiquidacion();">Generar liquidación</button>
            </td>

        </tr>
    @endif
    <tr>
        <td>
            <div class="form-group">
                <label for="documento_respuesta">Cargar Respuesta</label>
                <input type="file" accept="application/pdf" name="documento_respuesta" id="documento_respuesta"
                    class="form-control @error('documento_respuesta') is-invalid @enderror"
                    @if ($solicitud->dependencia == 'SALUD' || $solicitud->dependencia == 'PLANEACION') required @endif
                    @if ($config_novedades[0]->finaliza == 1) required @endif>
                @error('documento_respuesta')
                    <span class="invalid-feedback" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>
        @if ($config_novedades[0]->finaliza == 1 && $solicitud->dependencia == 'interior')
            <td>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio"
                        class="form-control @error('fecha_inicio') is-invalid @enderror"
                        @if ($config_novedades[0]->finaliza == 1) required @endif>
                    @error('fecha_inicio')
                        <span class="invalid-feedback" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </td>
            <td>
                <div class="row">
                    <div class="col">
                        <label for="fecha_fin">Fecha final</label>
                        <input type="date" name="fecha_fin" id="fecha_fin"
                            class="form-control @error('fecha_fin') is-invalid @enderror"
                            @if ($config_novedades[0]->finaliza == 1) required @endif>
                        @error('fecha_fin')
                            <span class="invalid-feedback" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="dif_dias_publicidad">Duración de la publicidad (días)</label>
                        <input class="form-control" type="text" name="dif_dias_publicidad"
                            id="dif_dias_publicidad" readonly>
                    </div>
                </div>
            </td>
        @else
            <td></td>
            <td></td>
        @endif
    </tr>
    <tr>
        <td>
            <div class="form-group">
                <button type="submit" id="myBtnEspacio" class="btn btn-round btn-middle btn-outline-info"
                    id="Boton">Actualizar
                </button>
            </div>
        </td>
        <td>
            <a href="/tramites/interior/publicidad/{{ $solicitud->modalidad }}"
                class="btn btn-round btn-high">Volver</a>
        </td>
        <td></td>
    </tr>
@endif
@endif
@endif

</form>
</tr>
{{-- fin del form --}}
