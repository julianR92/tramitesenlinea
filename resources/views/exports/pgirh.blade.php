<style>
        
    .tftable {font-size:10px;color:#333333;width:90%;border-width: 1px;border-color: black;border-collapse: collapse; overflow:auto;}
    .tftable th {font-size:12px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: black;text-align:left;}
    .tftable tr {background-color:#ffffff;}
    .tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: black;  text-align: center;
      /* center checkbox horizontally */
      vertical-align: middle;
      /* center checkbox vertically */}
    
    .tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: black;border-collapse: collapse; overflow:auto;}
    .tftable th {font-size:12px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: black;text-align:left;}
    .tftable tr {background-color:#ffffff;}
    .tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: black;  text-align: center;
      /* center checkbox horizontally */
      vertical-align: middle;
      /* center checkbox vertically */}
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0}
    input[type=number] {
      -moz-appearance:textfield;
    
    }

  table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
    
    </style>

<table class="tftable overflow-x-scroll" border="2" width="100%">
    <tr>
   <th colspan="17" style="text-align:center; font-size:14px; background-color:#6FF473;"><b>REPORTE FORMULARIO RH1 / GENERACIÓN DE RESIDUOS</b></th>
    </tr>
    <tr>
      <th colspan="3" style="font-size: 12px;">
      <b>Empresa:</b>
      </th>
      <th colspan="6" style="font-size: 12px;">{{$empresa}}</th>
      <th colspan="3" style="font-size: 12px;"><b>NIT:</b></th>
      <th colspan="5" style="font-size: 12px; text-align: left;">{{$nit}}</th>
    </tr>
    <tr>
        <th style="font-size: 9px;background-color:#89B9F2">Mes</th>
        <th style="font-size: 9px; background-color: #FAF670;">Biodegradables<br> (Kg)</th>
        <th style="font-size: 9px; background-color: #FAF670;">Reciclables<br> (Kg)</th>
        <th style="font-size: 9px; background-color: #FAF670;">Ordinarios y/o inertes<br> (Kg)"</th>
        <th style="font-size: 9px; background-color: #FA8480;">Biosanitarios<br> (Kg)</th>
        <th style="font-size: 9px; background-color: #FA8480;">Anatomopatologicos<br>  (Kg)</th>
        <th style="font-size: 9px; background-color: #FA8480;">Cortopunzantes<br>  (Kg)</th>
        <th style="font-size: 9px;background-color: #FA8480;">Animales<br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F8F58D;">Corrosivos<br> (Kg)</th >
        <th style="font-size: 9px;background-color: #F8F58D;">Tóxicos<br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F8F58D;">Inflamables<br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F8F58D;">Explosivos <br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F8F58D;">Reactivos<br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F7F5B4;">RAEES <br>(Kg)</th>
        <th style="font-size: 9px;background-color: #F7F5B4;">Luminarias <br>(Kg)</th>
        <th style="font-size: 9px;background-color: #F7F5B4;">Pilas <br> (Kg)</th>
        <th style="font-size: 9px;background-color: #F7F5B4;">Toner / Cartucho</th>
    </tr>
    @php
     $sumBiod =0; 
     $sumReci =0; 
     $sumOrd=0;
     $sumBios = 0; 
     $sumAna = 0; 
     $sumCorto = 0; 
     $sumAni = 0;
     $sumCorr=0; 
     $sumToxi=0;
     $sumInfla = 0;
     $sumExplo = 0;
     $sumRea = 0;
     $sumRAEES = 0;
     $sumLumi = 0;
     $sumPilas = 0;
     $sumToner = 0;
     @endphp                                 
   @foreach ($detalle as $item)
    <tr>
        <td>{{$item->mes}}</td>
        <td>{{$item->biodegradable}}</td>
        <td>{{$item->reciclables}}</td>
        <td>{{$item->ordinarios}}</td>
        <td>{{$item->biosanitarios}}</td>
        <td>{{$item->anatomopatologicos}}</td>
        <td>{{$item->cortopunzantes}}</td>
        <td>{{$item->animales}}</td>
        <td>{{$item->corrosivos}}</td>
        <td>{{$item->toxicos}}</td>
        <td>{{$item->inflamables}}</td>
        <td>{{$item->explosivos}}</td>
        <td>{{$item->reactivos}}</td>
        <td>{{$item->RAAES}}</td>
        <td>{{$item->luminarias}}</td>
        <td>{{$item->pilas}}</td>
        <td>{{$item->toner}}</td>
        
    </tr> 
    @php
     $sumBiod = $sumBiod + $item->biodegradable;
     $sumReci = $sumReci + $item->reciclables; 
     $sumOrd =  $sumOrd + $item->ordinarios;
     $sumBios = $sumBios + $item->biosanitarios; 
     $sumAna =  $sumAna + $item->anatomopatologicos; 
     $sumCorto = $sumCorto + $item->cortopunzantes; 
     $sumAni = $sumAni + $item->animales; 
     $sumCorr = $sumCorr + $item->corrosivos; 
     $sumToxi = $sumToxi + $item->toxicos; 
     $sumInfla = $sumInfla + $item->inflamables;
     $sumExplo = $sumExplo + $item->explosivos;
     $sumRea = $sumRea + $item->reactivos; 
     $sumRAEES = $sumRAEES + $item->RAAES;
     $sumLumi = $sumLumi + $item->luminarias;
     $sumPilas = $sumPilas + $item->pilas; 
      $sumToner = $sumToner + $item->toner;
    
    @endphp
    @endforeach  
    <tr>
      <td><b>Total</b></td>
      <td><b>{{$sumBiod}}</b></td>
      <td><b>{{$sumReci}}</b></td>
      <td><b>{{$sumOrd}}</b></td>
      <td><b>{{$sumBios}}</b></td>
      <td><b>{{$sumAna}}</b></td>
      <td><b>{{$sumCorto}}</b></td>
      <td><b>{{$sumAni}}</b></td>
      <td><b>{{$sumCorr}}</b></td>
      <td><b>{{$sumToxi}}</b></td>
      <td><b>{{$sumInfla}}</b></td>
      <td><b>{{$sumExplo}}</b></td>
      <td><b>{{$sumRea}}</b></td>
      <td><b>{{$sumRAEES}}</b></td>
      <td><b>{{$sumLumi}}</b></td>
      <td><b>{{$sumPilas}}</b></td>
      <td><b>{{$sumToner}}</b></td>
      
  </tr>                        
                                    
</table>