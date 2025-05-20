<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('general/barrios/get', 'GeneralController@getBarrios');
Route::get('general/parametros/get', 'GeneralController@getParametros');
Route::get('planeacion/tipo-licencia/get', 'PlaneacionController@planosTipoLicenciaGet');
Route::get('planeacion/modalidades/get/{tipo_licencia_id}', 'PlaneacionController@planosModalidadesGet');
Route::post('planeacion/planos/create', 'PlaneacionController@planosCreate');
Route::get('planeacion/planos/consultar', 'PlaneacionController@planosConsultar');
