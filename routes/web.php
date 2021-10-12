<?php

use App\Http\Controllers\PlaneacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login.index');
});

Route::post('/login', 'LoginController@login')->name('login');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard.index');
Route::get('/logout/{user}', 'LoginController@logout')->name('logout');

// experiencia globally

Route::post('/experiencia/tramites', 'HomeController@experienciaTramites')->name('experiencia.tramites');
Route::get('/tramites/trazabilidad/{radicado}/{tramite}', 'HomeController@trazabilidadTramites')->name('tramite.trazabilidad');

// RUTAS INHUMACIONES

Route::get('/inhumaciones', 'InhumacionesController@index')->name('inhumaciones.index');
Route::post('/inhumaciones/search', 'InhumacionesController@search')->name('inhumaciones.search');
Route::post('/inhumaciones/experiencia', 'InhumacionesController@experiencia')->name('inhumaciones.experiencia');

// RUTAS CATEGORIZACION DE PARQUEADEROS

Route::get('/categorizacion-parqueaderos', 'ParqueaderosController@index')->name('parqueaderos.index');
Route::post('/categorizacion-parqueaderos/store', 'ParqueaderosController@store')->name('parqueaderos.store');
Route::get('/categorizacion-parqueaderos/confirmacion', 'ParqueaderosController@confirmacion')->name('parqueaderos.confirmacion');
Route::get('/categorizacion-parqueaderos/finalizar', 'ParqueaderosController@end')->name('parqueaderos.finalizar');
Route::post('/categorizacion-parqueaderos/consulta','ParqueaderosController@consulta')->name('parqueadero.consulta');
Route::get('/categorizacion-parqueaderos/detalle/{id}', 'ParqueaderosController@detalle')->name('parqueadero.detalle');
Route::post('/categorizacion-parqueaderos/updateDocs', 'ParqueaderosController@updateDocs')->name('parqueadero.updateDocs');

// RUTAS EVENTOS PUBLICOS

Route::get('/eventos-publicos', 'EventosController@index')->name('eventos.index');
Route::post('/eventos-publicos/store', 'EventosController@store')->name('eventos.store');
Route::get('/eventos-publicos/confirmacion', 'EventosController@confirmacion')->name('eventos.confirmacion');
Route::get('/eventos-publicos/finalizar', 'EventosController@end')->name('eventos.finalizar');
Route::post('/eventos-publicos/consulta','EventosController@consulta')->name('eventos.consulta');
Route::get('/eventos-publicos/detalle/{id}', 'EventosController@detalle')->name('eventos.detalle');
Route::post('/eventos-publicos/updateDocs', 'EventosController@updateDocs')->name('eventos.updateDocs');


// RUTAS METROLINEA

Route::get('/registro-metrolinea', 'MetrolineaController@index')->name('metrolinea.index');
Route::post('/registro-metrolinea/store', 'MetrolineaController@store')->name('metrolinea.store');
Route::get('/registro-metrolinea/confirmacion', 'MetrolineaController@confirmacion')->name('metrolinea.confirmacion');
Route::get('/registro-metrolinea/finalizar', 'MetrolineaController@end')->name('metrolinea.finalizar');
Route::post('/registro-metrolinea/consulta', 'MetrolineaController@consulta')->name('metrolinea.consulta');
Route::post('/registro-metrolinea/entidades', 'MetrolineaController@entidades')->name('metrolinea.entidades');
Route::get('/registro-metrolinea/detalle/{id}', 'MetrolineaController@detalle')->name('metrolinea.detalle');
Route::post('/registro-metrolinea/updateDocs', 'MetrolineaController@updateDocs')->name('metrolinea.updateDocs');



// RUTAS DE PUBLICIDAD EXTERIOR

Route::get('/publicidad-exterior', 'PublicidadController@index')->name('publicidad.index');




Route::group(['middleware' => ['role:SUPER-ADMIN']], function () {

    //RUTAS DE ROLES

Route::get('/dashboard/role', 'RoleController@index')->name('role.index');
Route::post('/dashboard/role/store', 'RoleController@store')->name('role.create');
Route::get('/roles/create','RoleController@create')->name('role.add');
Route::get('dashboard/roles/{id}/edit','RoleController@edit')->name('role.edit');
Route::post('dashboard/roles/update', 'RoleController@update')->name('role.update');
route::get('/dashboard/roles/destroy/{id}', 'RoleController@destroy')->name('role.destroy');
Route::get('/roles/ver/{id}','RoleController@verPermisos')->name('roles.ver');


//Rutas de permisos

Route::get('/dashboard/permission', 'PermissionController@index')->name('permisos.index');
Route::get('/dashboard/permission/{id}/edit', 'PermissionController@edit')->name('permisos.editar');
Route::post('/dashboard/permission/store', 'PermissionController@store')->name('permisos.create');
Route::post('/dashboard/permission/update', 'PermissionController@update')->name('permisos.update');
Route::get('/dashboard/permission/destroy/{id}','PermissionController@destroy')->name('permisos.destroy');
    //
});


Route::group(['middleware' => ['role:SUPER-ADMIN|ADMIN']], function () {

// RUTAS DE USUARIO

Route::get('/dashboard/users', 'UserController@index')->name('user.index');
Route::get('/dashboard/users/{id}/roleEdit', 'UserController@editRoles')->name('user.editRoles');
Route::post('/dashboard/users/roleUpdate', 'UserController@assingRoles')->name('user.update');
Route::get('/dashboard/users/{id}/permissionsEdit', 'UserController@editPermission')->name('user.editPermissions');
Route::post('/dashboard/users/permissionsUpdate', 'UserController@AssingPermissions')->name('user.updatePermissions');
Route::get('/dashboard/users/admin','UserController@indexAdmin')->name('user.indexAdmin');
Route::get('/dashboard/users/{id}/permissionsEditAdmin', 'UserController@editPermissionAdmin')->name('user.editPermissionsAdmin');

});

// RUTAS DE PLANEACION VER TRAMITES
Route::group(['middleware' => ['role:SUPER-ADMIN|PLANEACION']], function () {
    Route::get('/tramites/planeacion','PlaneacionController@index')->name('planeacion.index');
});

Route::group(['middleware' => ['role_or_permission:SUPER-ADMIN|PLANEACION|editar-tramite']], function () {
    Route::get('/tramites/planeacion/espacio','PlaneacionController@espacioIndex')->name('espacio.index');
    Route::get('/tramites/planeacion/espacio/{id}', 'PlaneacionController@detalleSolicitud')->name('espacio.detalle');
    Route::post('/tramites/planeacion/espacio/update','PlaneacionController@updateSolicitud')->name('espacio.update');
    Route::get('/tramites/planeacion/espacio/document/{id}', 'PlaneacionController@documentSolicitud')->name('espacio.documento');

    // rutas tramite categorizacion de parqueaderos

    Route::get('/tramites/planeacion/parqueaderos/', 'PlaneacionController@indexParqueaderos')->name('planeacion.parqueaderos.index');
    Route::get('/tramites/planeacion/parqueadero/{id}','PlaneacionController@parqueaderoDetalle')->name('planeacion.parqueaderos.detalle');
    Route::get('/tramites/planeacion/parqueadero/auditoria/{id}','PlaneacionController@parqueaderoDetalleAuditoria')->name('planeacion.parqueaderos.detalle.auditoria');


});

// RUTAS DE INTERIOR VER TRAMITES
Route::group(['middleware' => ['role:SUPER-ADMIN|SEC_GOBIERNO']], function () {
    Route::get('/tramites/interior','InteriorController@index')->name('interior.index');
});

Route::group(['middleware' => ['role_or_permission:SUPER-ADMIN|SEC_GOBIERNO|editar-tramite']], function () { 
    Route::get('/tramites/interior/parqueaderos','InteriorController@parqueaderoIndex')->name('interior.parqueaderos.index');
    Route::get('/tramites/interior/parqueadero/{id}','InteriorController@parqueaderoDetalle')->name('interior.parqueaderos.detalle');
    Route::post('tramites/interior/parqueaderos/update/', 'InteriorController@parqueaderoUpdate' )->name('interior.parqueaderos.update');
    
    // tramite de eventos
    Route::get('/tramites/interior/eventos','InteriorController@eventosIndex')->name('interior.eventos.index');
    Route::get('/tramites/interior/evento/{id}','InteriorController@eventoDetalle')->name('interior.eventos.detalle');
    Route::post('tramites/interior/eventos/update/', 'InteriorController@eventosUpdate' )->name('interior.eventos.update');
    
    
});















