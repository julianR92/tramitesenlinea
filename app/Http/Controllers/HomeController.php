<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExperienciaGlobal;

class HomeController extends Controller
{
    public function __construct()

  {
      $this->middleware('auth', ['only' => ['dashboard']]);
  }

    public function dashboard(){
        return view('dashboard.index');
    }

    public function experienciaTramites(Request $request){

        $experiencia = new ExperienciaGlobal;
        $experiencia->valor = $request->valor;
        $experiencia->tramite = $request->tramite;
        if ($request->sugerencias == null) {
            $experiencia->sugerencias = "SIN SUGERENCIAS";
        } else {
            $experiencia->sugerencias = $request->sugerencias;
        }
        if ($experiencia->save()) {
            $resp = ["success" => true];
            return response()->json($resp);
        } else {
            $resp = ["success" => false];
            return response()->json($resp);
        }




    }
}
