<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;


class ReportesPgirh implements FromView, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $id;
    public $empresa;
    public $nit;
    public $gestor;
        
    public function __construct($id,$empresa,$nit,$gestor)
    {
        $this->id = $id; // errro en en linea
        $this->empresa = $empresa;
        $this->nit = $nit;
        $this->gestor = $gestor;
    }

    public function collection()
    {
        //
    }

    public function view(): View
    {
        $sql = 'SELECT 
        mes,
        SUM(IF(residuo_id = 1, kilos, 0)) AS biodegradable,
        SUM(IF(residuo_id = 2, kilos, 0)) AS reciclables,  
        SUM(IF(residuo_id = 3, kilos, 0)) AS ordinarios,  
        SUM(IF(residuo_id = 4, kilos, 0)) AS biosanitarios,  
        SUM(IF(residuo_id = 5, kilos, 0)) AS anatomopatologicos,  
        SUM(IF(residuo_id = 6, kilos, 0)) AS cortopunzantes, 
        SUM(IF(residuo_id = 7, kilos, 0)) AS animales,  
        SUM(IF(residuo_id = 8, kilos, 0)) AS corrosivos,  
        SUM(IF(residuo_id = 9, kilos, 0)) AS toxicos,
        SUM(IF(residuo_id = 10, kilos, 0)) AS inflamables,
        SUM(IF(residuo_id = 11, kilos, 0)) AS explosivos,  
        SUM(IF(residuo_id = 12,  kilos, 0)) AS reactivos,
        SUM(IF(residuo_id = 13,  kilos, 0)) AS RAAES,   
        SUM(IF(residuo_id = 14,  kilos, 0)) AS luminarias,
        SUM(IF(residuo_id = 15,  kilos, 0)) AS pilas,
        SUM(IF(residuo_id = 16,  kilos, 0)) AS toner         
        FROM  reporte_detalle WHERE reporte_id = :id GROUP BY mes ORDER BY mes ASC;';

        $detalle = DB::connection('mysql3')->select($sql,['id'=>$this->id]);

        return view('exports.pgirh', [
            'detalle' => $detalle, 
            'empresa' => $this->empresa, 
            'nit'=>$this->nit, 
            'gestor'=>$this->gestor
        ]);
    }

    public function title(): string
    {
        return 'Reporte PGIRH';
    }
}
