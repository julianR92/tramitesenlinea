<?php

namespace App\Http\Controllers;
use App\Barrio;
use App\Parametro;

use Illuminate\Http\Request;

class PublicidadController extends Controller
{

    public function index(){

    $Parametros1 = Parametro::where('ParNomGru', 'LETRA')->get();
       $Parametros2 = Parametro::where('ParNomGru','ABREDIR')->get();
        $Barrios = Barrio::all();   
              
       return view('tramites.publicidad.index', compact('Parametros1', 'Parametros2', 'Barrios'));

    }
    

}
