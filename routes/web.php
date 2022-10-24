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


// RUTAS DE ENCUESTA POT

Route::get('/planeacion/encuesta-pot','PlaneacionController@indexPot')->name('pot.index');
Route::post('/planeacion/encuesta-pot/barrios','PlaneacionController@barriosComunas')->name('pot.barrios');
Route::post('/planeacion/encuesta-pot/veredas','PlaneacionController@veredaCorregimiento')->name('pot.veredas');
Route::post('/planeacion/encuesta-pot/validacionDocumento','PlaneacionController@validacionDocumento')->name('pot.documento');
Route::post('/planeacion/encuesta-pot/store','PlaneacionController@potStore')->name('pot.store');
Route::get('/planeacion/encuesta-pot/confirmacion','PlaneacionController@confirmacionPot')->name('pot.confirmacion');







// RUTAS DE PUBLICIDAD EXTERIOR

Route::get('/publicidad-exterior', 'PublicidadController@index')->name('publicidad.index');


//RUTAS DE ESPECTACULOS Publicos
Route::get('/espectaculos-publicos', 'EspectaculosController@index')->name('espectaculos.index');
Route::post('/espectaculos-publicos/store', 'EspectaculosController@store')->name('espectaculos.store');
Route::get('/espectaculos-publicos/confirmacion', 'EspectaculosController@confirmacion')->name('espectaculos.confirmacion');
Route::get('/espectaculos-publicos/finalizar', 'EspectaculosController@end')->name('espectaculos.finalizar');
Route::post('/espectaculos-publicos/consulta','EspectaculosController@consulta')->name('espectaculos.consulta');
Route::get('/espectaculos-publicos/detalle/{id}', 'EspectaculosController@detalle')->name('espectaculos.detalle');
Route::post('/espectaculos-publicos/updateDocs', 'EspectaculosController@updateDocs')->name('espectaculo.updateDocs');
Route::get('/espectaculos-publicos/cancelar/{id}', 'EspectaculosController@cancelar')->name('espectaculos.cancelar');
Route::post('/espectaculos-publicos/cancelarSolicitud/', 'EspectaculosController@cancelarSolicitud')->name('espectaculos.cancelarSolicitud');


// RUTAS CERTIFICADO DE DISCAPACIDAD
Route::get('/certificado-discapacidad', 'DiscapacidadController@index')->name('discapacidad.index');
Route::post('/certificado-discapacidad/store', 'DiscapacidadController@store')->name('discapacidad.store');
Route::get('/certificado-discapacidad/confirmacion', 'DiscapacidadController@confirmacion')->name('discapacidad.confirmacion');
Route::get('/certificado-discapacidad/finalizar', 'DiscapacidadController@end')->name('discapacidad.finalizar');
Route::post('/certificado-discapacidad/consulta','DiscapacidadController@consulta')->name('discapacidad.consulta');
Route::get('/certificado-discapacidad/detalle/{id}', 'DiscapacidadController@detalle')->name('discapacidad.detalle');
Route::post('/certificado-discapacidad/updateDocs', 'DiscapacidadController@updateDocs')->name('discapacidad.updateDocs');


//RUTAS FAMILIA Familia
Route::get('/familia-lactante','SaludController@indexFamilia')->name('familia.index');
Route::post('/familia-lactante/store','SaludController@storeFamilia')->name('familia.store');
Route::get('/familia-lactante/confirmacion', 'SaludController@confirmacion')->name('familia.confirmacion');
Route::get('/familia-lactante/finalizar', 'SaludController@end')->name('familia.finalizar');


Route::get('/pruebas', 'EspectaculosController@pruebas')->name('pruebas.pruebas');
Route::get('/pruebas-estilos', function() {
    return view('pruebas');
});






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

// RUTAS DE HACIENDA PARA VER TRAMITES
Route::group(['middleware' => ['role:SUPER-ADMIN|HACIENDA-SFI']], function () {
    Route::get('/tramites/hacienda','HaciendaController@index')->name('hacienda.index');
});

Route::group(['middleware' => ['role_or_permission:SUPER-ADMIN|HACIENDA-SFI|editar-tramite']], function () { 
    Route::get('/tramites/hacienda/espectaculos','HaciendaController@espectaculoIndex')->name('hacienda.espectaculos.index');
    Route::get('/tramites/hacienda/espectaculos/{id}','HaciendaController@espectaculoDetalle')->name('hacienda.espectaculos.detalle');
    Route::post('tramites/hacienda/espectaculos/update/', 'HaciendaController@espectaculoUpdate' )->name('hacienda.espectaculos.update');
    
    // // tramite de eventos
    // Route::get('/tramites/interior/eventos','InteriorController@eventosIndex')->name('interior.eventos.index');
    // Route::get('/tramites/interior/evento/{id}','InteriorController@eventoDetalle')->name('interior.eventos.detalle');
    // Route::post('tramites/interior/eventos/update/', 'InteriorController@eventosUpdate' )->name('interior.eventos.update');

    
    
});

// RUTAS DE JURIDICA PARA VER TRAMITES
Route::group(['middleware' => ['role:SUPER-ADMIN|JURIDICA']], function () {
    Route::get('/tramites/juridica','JuridicaController@index')->name('juridica.index');
});
Route::group(['middleware' => ['role_or_permission:SUPER-ADMIN|canal-denuncia']], function () {
    Route::get('/tramites/juridica/rita','JuridicaController@main')->name('juridica.rita.main');
    Route::get('/tramites/juridica/rita/denuncias','JuridicaController@denuncias')->name('juridica.rita.index');
    Route::get('/tramites/juridica/rita/{id}','JuridicaController@denunciasDetalle')->name('juridica.rita.detalle');
    Route::post('/tramites/juridica/rita','JuridicaController@updateDenuncia')->name('juridica.rita.update');
});


//RUTAS PARA SALUD
//publicidad parte salud
Route::group(['middleware' => ['role:SUPER-ADMIN|SALUD|SEC SALUD']], function () {
    Route::get('/tramites/salud','SaludController@index')->name('salud.index');
 });

 Route::group(['middleware' => ['role_or_permission:SUPER-ADMIN|SEC SALUD|editar-tramite']], function () {
    //tramite publicdad exterior
    // Route::get('/tramites/salud/publicidad','PublicidadAdmin@salud')->name('salud.publicidad.index');
   // Route::get('/tramites/salud/publicidad/detalle/{id}','PublicidadAdmin@detalle')->name('salud.publicidad.detalle');
    //Route::post('/tramites/salud/publicidad/','SaludController@publicidadUpdate')->name('salud.publicidad.update');

    //PGIRH
     Route::get('/tramites/salud/pgirh','SaludController@indexEmpresas')->name('salud.pgirh.index');
     Route::get('/tramites/salud/pgirh/reportes/{id}','SaludController@reportesEmpresas')->name('salud.pgirh.reportes');
     Route::get('/tramites/salud/pgirh/excel/{id}/{empresa}/{nit}/{gestor}','SaludController@excelDetalle')->name('salud.pgirh.detalle');
   
    //AUTORIZACION DE LA CERTIFICACION DE DISCAPCIDAD

    Route::get('/tramites/salud/discapacidad','SaludController@indexDiscapacidad')->name('salud.discapacidad.index');
    Route::get('/tramites/salud/discapacidad/{id}','SaludController@discapacidadDetalle')->name('salud.discapacidad.detalle');
    Route::post('tramites/salud/discapacidad/update/', 'SaludController@discapacidadUpdate' )->name('salud.discapacidad.update');
    
     

 });
















