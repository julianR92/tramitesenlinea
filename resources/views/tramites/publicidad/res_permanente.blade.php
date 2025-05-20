<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resolución Permanente</title>
</head>
<style>
    @page {
        size: legal;
        margin: 0;
    }

    @media print {

        html,
        body {
            font-family: "Arial", serif;
            font-size: 12px;
        }
    }

    @page {
        margin: 30px 50px 50px;
    }


    .page-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        margin: 0;
        padding: 0;
        z-index: 1000;
    }

    footer {
        text-align: right;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100px;
        color: black;
        font-size: 10px;
        font-family: Arial, sans-serif;
        font-style: italic;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: none;
    }

    td {
        padding: 5px;
        margin: 0;
        border: 1px solid rgb(75, 75, 75);
        font-size: 12px;
    }
</style>

<body>
    <div class="page-header">
        <table style="margin-bottom:0px;padding-bottom:0px;">
            <tr>
                <!-- Columna de la imagen -->
                <td style="width: 120px; vertical-align: top; border: none;">
                    <img src="{{ public_path('img/ALCALDIA.png') }}" width="120px" alt="Logo" />
                </td>

                <!-- Columna de la tabla -->
                <td style="vertical-align: top;border: none;">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    DEPENDENCIA: SECRETARÍA DE HACIENDA
                                </td>
                                <td>
                                    No. Consecutivo: <br>
                                    {{ $consecutivo }} - {{ $fecha }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    OFICINA PRODUCTORA:<br>
                                    SECRETARÍA DE HACIENDA<br>
                                    Código TRD: 3100
                                </td>
                                <td>
                                    SERIE/Subserie: RESOLUCIONES<br>
                                    Código Serie/Subserie (TRD): 3100.68 /
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <h4 style="text-align: center;padding-top:0px;margin-top:0px;">
            SECRETARIA DE HACIENDA
        </h4>
        <h4 style="text-align: center;">
            @php
                \Carbon\Carbon::setLocale('es');
            @endphp
            RESOLUCION No. {{ $consecutivo }} DE
            {{ strtoupper(\Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM [de] YYYY')) }}
        </h4>
        <h4 style="text-align: center;">
            "POR MEDIO DE LA CUAL SE PRACTICA UNA LIQUIDACION OFICIAL DEL IMPUESTO DE PUBLICIDAD EXTERIOR VISUAL"
        </h4>
    </div>

    <div style="margin-top: 280px;">

        <table style="width: 100%; table-layout: fixed; font-size: 10px; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="padding: 2px;">CONTRIBUYENTE RESPONSABLE</td>
                    <td style="padding: 2px;">
                        {{ $persona->PersonaTipDoc == 'NIT'
                            ? $persona->PersonaRazon
                            : $persona->PersonaNombre . ' ' . $persona->PersonaApe }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 2px;">NIT O CEDULA DE CIUDADANÍA</td>
                    <td style="padding: 2px;">{{ $persona->PersonaDoc }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">REPRESENTANTE LEGAL</td>
                    <td style="padding: 2px;">
                        {{ $persona->PersonaTipDoc == 'NIT'
                            ? $persona->PersonaRazon
                            : $persona->PersonaNombre . ' ' . $persona->PersonaApe }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 2px;">DIRECCIÓN</td>
                    <td style="padding: 2px;">{{ $persona->PersonaDir }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">TIPO DE PUBLICIDAD</td>
                    <td style="padding: 2px;">{{ $solicitud->modalidad }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">CANTIDAD</td>
                    @php
                        $f = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
                    @endphp
                    <td style="padding: 2px;">{{ $f->format($solicitud->numero_elementos) }}
                        ({{ $solicitud->numero_elementos }})
                        elemento(s)</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">DIMENSIÓN</td>
                    <td style="padding: 2px;">{{ $detalle->area_total_elemento }} MTS2</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">UBICACIÓN</td>
                    <td style="padding: 2px;">{{ $detalle->direccion_elemento }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px;">PERIODO DE COBRO</td>
                    @php
                        use Carbon\Carbon;
                    @endphp
                    <td style="padding: 2px;">Del
                        {{ \Carbon\Carbon::parse($fecha_inicial)->translatedFormat('d
                                                                  \d\e F \d\e Y') }}
                        al {{ \Carbon\Carbon::parse($fecha_final)->translatedFormat('d \d\e F \d\e Y') }}
                    </td>
                </tr>
                <tr>
                    <td>CONTENIDO DEL AVISO</td>
                    <td>{{ $detalle->descripcion_elemento == '' ? 'Publicidad exterior visual' : $detalle->descripcion_elemento }}
                    </td>
                </tr>
            </tbody>
        </table>

        <h5 style="text-align: center;">
            EL PRESENTE ACTO ADMINISTRATIVO SE PROFIERE DE CONFORMIDAD CON EL DECRETO 0040 DE 2022
            <br>
            <span style="font-italic;">
                "Por medio del cual se compila el régimen tributario del Municipio de Bucaramanga"
            </span>
        </h5>

        <p style="text-align: center;">
            C O N S I D E R A C I O N E S:
        </p>

        <ol type="a" style="font-size:15px;">
            <li style="margin-bottom: 15px;text-align: justify;">Que de conformidad con lo establecido en el Artículo 47
                del
                Acuerdo 026 de 2018, la Secretaria de Hacienda
                está a
                cargo de la administración y control del Impuesto de publicidad exterior visual y demás funciones de
                fiscalización, liquidación oficial, discusión y devolución del tributo conforme el Decreto 040 del 25
                marzo
                de
                2022.</li>
            <li style="margin-bottom: 15px;text-align: justify;">Que en desarrollo de la ley 140 de 1994, por la cual se
                reglamenta la publicidad exterior visual en el
                territorio
                nacional, se expidió el Decreto 0040 de 2022, el cual en los artículos 122 y siguientes regula los
                aspectos
                sobre
                publicidad exterior visual en el Municipio de Bucaramanga. Establece la norma citada que la publicidad
                de
                carácter
                permanente es decir aquella que se conserve por un término superior a treinta (30) días y cuya dimensión
                es
                de
                cuarenta y ocho (48) metros cuadrados causará impuesto equivalente a cuatro (4) salarios mínimos legales
                mensuales, por año. Señala igualmente que la publicidad de carácter permanente y cuyas dimensiones sean
                superiores
                o inferiores a cuarenta y ocho (48) metros cuadrados causará un impuesto proporcional a lo establecido
                en el
                inciso anterior.</li>
            <li style="margin-bottom: 15px;text-align: justify;">Que el Impuesto a la publicidad exterior visual se
                causa
                al
                momento de la solicitud de autorización y registro
                de
                la valla, conforme señala el Artículo 125 del Decreto 040 de 2022.</li>
            <li style="margin-bottom: 15px;text-align: justify;">Que el artículo 131 del Decreto 0040 de 2022, señala
                que
                la
                Secretaria de Hacienda Municipal practicará
                liquidación oficial del impuesto de publicidad exterior visual a partir de la información que le
                suministre
                la
                secretaria del Interior sobre las solicitudes de registro, conforme las disposiciones reglamentarias
                vigentes.
            </li>
            @php
                $fo = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
                $numeroElementos = $solicitud->numero_elementos;
                $numeroEnLetras = $fo->format($numeroElementos);
                $unidad = $numeroElementos == 1 ? 'UNIDAD PUBLICITARIA' : 'UNIDADES PUBLICITARIAS';
            @endphp
            <li style="margin-bottom: 15px;text-align: justify;">Que el contribuyente antes mencionado instaló en esta
                jurisdicción {{ strtoupper($numeroEnLetras) }} ({{ str_pad($numeroElementos, 2, '0', STR_PAD_LEFT) }}) {{ $unidad }},
                constituyéndose
                en sujeto pasivo del impuesto de publicidad exterior visual y conforme a la comunicación interna emanada
                de
                la
                secretaria del Interior consta que cuenta con el visto bueno de aprobación de instalación.</li>
            <li style="margin-bottom: 15px;text-align: justify;">
                Que los contribuyentes responsables de los impuestos que no cancele oportunamente deberán liquidar y
                pagar
                intereses
                moratorios, por cada día calendario de retardo en el pago. Para tal efecto, la totalidad de los
                intereses de
                mora, se
                liquidarán con base en la tasa de interés vigente en el momento del respectivo pago, calculada de
                conformidad
                con lo
                previsto en el artículo 635 del Estatuto tributario Nacional, modificado por el artículo 279 de la ley
                1819
                de
                2016.
            </li>
        </ol>
    </div>
    <footer>
        <p style="text-align:right;">
            Calle 35 N° 10 – 43 Centro Administrativo, Edificio Fase I<br>
            Carrera 11 N° 34 – 52, Edificio Fase II,<br>
            Conmutador: (57-7) 6337000 Fax 6521777<br>
            Página Web: www.bucaramanga.gov.co,<br>
            Código postal 680006<br>
            Bucaramanga, Departamento de Santander, Colombia
        </p>
    </footer>
    <div style="page-break-after: always;"></div>

    <div style="margin-top: 280px;">
        <p style="text-align: center;">
            R E S U E L V E:
        </p>
        <p>
            <b>ARTÍCULO PRIMERO:</b> Practicar la siguiente liquidación del impuesto de Publicidad Exterior Visual,
            acorde
            con las
            características antes mencionadas así:
        </p>
        <table style="width: 100%; table-layout: fixed; font-size: 10px; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td><b>NOMBRE DEL SOLICITANTE</b></td>
                    <td>{{ $persona->PersonaTipDoc == 'NIT' ? $persona->PersonaRazon : $persona->PersonaNombre . ' ' . $persona->PersonaApe }}
                    </td>
                </tr>
                <tr>
                    <td><b>NIT- CC</b></td>
                    <td>{{ $persona->PersonaDoc }}</td>
                </tr>
            </tbody>
        </table>
        @php
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
            $individual = (int) $total / (int) $solicitud->numero_elementos;

            $mes = ceil($dias / 30);

            if ($dias > 30) {
                $tipo_liquidacion = 'PERMANENTE';
            } else {
                $tipo_liquidacion = 'TRANSITORIA';
            }

            $formatter = new \NumberFormatter('es_CO', \NumberFormatter::CURRENCY);
        @endphp
        <table style="width: 100%; table-layout: fixed; font-size: 10px; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="font-weight: bold">TOTAL DIMENSION</td>
                    <td style="font-weight: bold">VALOR IMPUESTO ELEMENTO PUBLICITARIO INDIVIDUAL</td>
                    <td style="font-weight: bold">CANTIDAD DE PUBLICIDAD</td>
                    <td style="font-weight: bold">PERIODO DE COBRO</td>
                    <td style="font-weight: bold">N.º DE MESES</td>
                    <td style="font-weight: bold">TOTAL</td>
                </tr>
                <tr>
                    <td style="text-align: center;">{{ $detalle->area_total_elemento }} MTS2</td>
                    <td style="text-align: center;">{{ $formatter->format((int) $individual) }}</td>
                    <td style="text-align: center;">{{ $solicitud->numero_elementos }}</td>
                    <td style="text-align: center;">Del
                        {{ \Carbon\Carbon::parse($fecha_inicial)->translatedFormat('d
                                                               \d\e F \d\e Y') }}
                        al {{ \Carbon\Carbon::parse($fecha_final)->translatedFormat('d \d\e F \d\e Y') }}</td>
                    <td style="text-align: center;">{{ $diferencia }}</td>
                    <td style="text-align: center;">{{ $formatter->format((int) $total) }}</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 10px; text-align: right; font-size: 15px; font-weight: bold;">
            Total a pagar: {{ $formatter->format((int) $total) }}
        </div>
        <br>
        <p style="text-align: justify;"><b>ARTICULO SEGUNDO:</b> Los responsables de este impuesto que por cualquier
            razón desistan de la fijación de la publicidad
            deberán informarlo a la Secretaria de Hacienda Municipal, con anticipación a la fecha señalada para la
            Fijación, a fin
            de que esta secretaria puede constatar la inexistencia del hecho generador. Mientas el responsable no
            informe dicha
            circunstancia estará obligado a cancelar el impuesto liquidado por concepto de Publicidad exterior visual.
        </p>

        <p style="text-align: justify;"><b>ARTÍCULO TERCERO:</b> Contra la presente resolución procede al recurso de
            reconsideración, interpuesto ante la Secretaria de
            Hacienda, dentro de los dos (2) meses siguientes a la notificación del mismo, de conformidad con lo
            establecido en el
            artículo 361 del Decreto 0040 de 2022 Compilación Régimen Tributario en concordancia con el Art. 720 del
            Estatuto
            Tributario Nacional.</p>

        <p style="text-align: justify;"><b>ARTICULO CUARTO:</b> Enviar copia de la presente Resolución al contribuyente
            y consecutivo para los fines pertinentes.</p>


        <p style="text-align: justify;"><b>ARTICULO QUINTO:</b> Notificar la presente resolución según los términos y
            procedimientos establecidos en los artículos 227
            y siguientes del Decreto 0040 de marzo 25 de 2022 mediante el cual se compilo el número de régimen
            tributario del
            Municipio de Bucaramanga.</p>

        <h5 style="margin-top:135px;margin-bottom:100px; text-align: center;">
            REYNALDO JOSÉ D'SILVA URIBE
            <br>
            Secretario de Hacienda
        </h5>

        <p style="text-algin:right;font-size:10px;"><b>Proyectó:</b> Luz Angela Caballero Corzo/Contratista Impuestos
        </p>

        <p style="text-algin:right;font-size:10px;"><b>Revisó:</b> Lina María Manrique Duarte/ Subsecretaria de
            Hacienda(e)
        </p>
    </div>
    <footer>
        <p style="text-align:right;">
            Calle 35 N° 10 – 43 Centro Administrativo, Edificio Fase I<br>
            Carrera 11 N° 34 – 52, Edificio Fase II,<br>
            Conmutador: (57-7) 6337000 Fax 6521777<br>
            Página Web: www.bucaramanga.gov.co,<br>
            Código postal 680006<br>
            Bucaramanga, Departamento de Santander, Colombia
        </p>
    </footer>




</body>

</html>
