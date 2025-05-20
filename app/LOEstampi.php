<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LOEstampi extends Model
{  
    protected $connection = 'sqlsrv';
    protected $table = 'LOESTAMPI';
    protected $primaryKey = 'LoNroLiq';
    public $timestamps = false;

    protected $fillable=[

        "LoTipDoc",
        "LoNumIde",
        "LoNumActAdm",
        "LoFecAct",
        "LoEnti",
        "LoCargo",
        "LoCodigo",
        "LoGrado",
        "LoValMen",
        "LoTipNom",
        "LoEmail",
        "LoTel",
        "LoNombre",
        "LoRadicado",
        "AdjActPos",          
        "AdjNumIde", 
        "AdjCertSal",
        "LoEstSol",
        "LoEst"


    ];
    public function getDateFormat(){
        return 'Y-d-m H:i:s';
        }


}