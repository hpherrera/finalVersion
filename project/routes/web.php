<?php

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


Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/pick_role', 'HomeController@pick');
Route::post('/login_with_role', 'HomeController@pickRole');
Route::post('/change','HomeController@changepass');

//Proyecto
Route::get('/indexProyecto', 'ProyectoController@index');
Route::get('proyecto/create', 'ProyectoController@create');
Route::post('proyecto', 'ProyectoController@store'); 
Route::get('proyecto/{proyecto}/edit', 'ProyectoController@edit');
Route::post('proyecto/update/{proyecto}', 'ProyectoController@update');
Route::post('proyecto/delete/{proyecto}','ProyectoController@delete');
Route::get('proyecto/{proyecto}/info' ,'ProyectoController@info');

//Persona
Route::get('/index', 'PersonaController@index');
Route::get('/create', 'PersonaController@create');
Route::post('persona', 'PersonaController@store'); 
Route::get('persona/{persona}/edit', 'PersonaController@edit');
Route::post('persona/update/{persona}', 'PersonaController@update');
Route::post('persona/delete/{persona}','PersonaController@delete');

//Hito
Route::get('/indexHito', 'HitoController@index');
Route::get('hito/create', 'HitoController@create');
Route::post('hito', 'HitoController@store');
Route::get('hito/{hito}/info' ,'HitoController@info');
Route::post('hito/delete/{hito}','HitoController@delete');
Route::get('hito/{hito}/edit', 'HitoController@edit');
Route::post('hito/update/{hito}', 'HitoController@update');
Route::post('tarea/create/{hito}', 'HitoController@addTarea');

//Tarea
Route::get('/indexTarea', 'TareaController@index');
Route::get('tarea/create', 'TareaController@create');
Route::post('tarea', 'TareaController@store');
Route::get('tarea/{tarea}/info' ,'TareaController@info');
Route::post('tarea/delete/{tarea}','TareaController@delete');
Route::get('tarea/{tarea}/edit', 'TareaController@edit');
Route::post('tarea/update/{tarea}', 'TareaController@update');
Route::post('/fechas' ,'TareaController@fechas');

//Entregable
Route::get('/indexEntregable', 'EntregableController@index');
Route::get('entregable/{tarea}/create', 'EntregableController@create');
Route::post('entregable', 'EntregableController@store');
Route::post('entregableRevision', 'EntregableController@store2');
Route::get('entregable/{entregable}/info', 'EntregableController@info');
Route::get('entregable/create2', 'EntregableController@create2');
Route::get('entregable/{entregable}/edit', 'EntregableController@edit');
Route::post('entregable/update/{entregable}', 'EntregableController@update');
Route::post('entregable/delete/{entregable}','EntregableController@delete');
Route::get('entregable/{entregable}/Descargar', 'EntregableController@descargar');

//Profesor Guia
Route::get('/indexProfesorGuia', 'ProfesorGuiaController@index');
Route::get('estudiantes', 'ProfesorGuiaController@estudiantes');
Route::get('proyectocreate', 'ProyectoController@create');
Route::get('estudiantecreate', 'EstudianteController@create');
Route::post('proyecto', 'ProyectoController@store'); 
Route::get('reunion','ProfesorGuiaController@planificacion');
Route::post('createreunion','ProfesorGuiaController@createreunion');
Route::post('/tarea/reuniones','ProfesorGuiaController@show_reunion');
Route::post('/addinvitado/{proyecto}','ProfesorGuiaController@addInvitado');
Route::post('/updateinvitado','ProfesorGuiaController@updateInvitado');
Route::post('/editinvitado','ProfesorGuiaController@editInvitado');
Route::post('/removeinvitado/{proyecto}','ProfesorGuiaController@removeInvitado');
Route::post('datosEstudianteProyecto','ProfesorGuiaController@estudiante');
Route::post('/estudianteedit','ProfesorGuiaController@editestudiante');

//Profesor curso
Route::get('/estudiante/{proyecto}/info', 'EstudianteController@historial');

//Repositorio
Route::get('indexRepositorio','RepositorioController@index');

//Funcionario
Route::get('estudents','FuncionarioController@all_students');
Route::get('estudentsEgresados','FuncionarioController@students');
Route::get('createoldproyect','FuncionarioController@create');
Route::get('stadistic','FuncionarioController@stadistic');
Route::get('promedioduracion','FuncionarioController@promedio_egreso');
Route::get('/proyecto/{proyecto}/ver','FuncionarioController@proyectoinfo');
Route::get('proyectos','FuncionarioController@index');

//Comentario
Route::get('comentario','ComentarioController@store');
Route::post('/comentario/eliminar/{comentario}','ComentarioController@delete');
Route::post('/comentario/crear','ComentarioController@create');
Route::post('/comentario/editar/{comentario}','ComentarioController@edit');

//Notificaciones
Route::post('/isacepted','NotificacionController@isAcepted');
Route::get('notification/index', 'NotificacionController@index');
Route::post('/updateNotificaction' ,'NotificacionController@update');
Route::post('/viewNotificaction' ,'NotificacionController@view');
Route::post('/show_entregables' ,'NotificacionController@entregables');

//Planificacion
Route::get('planificacion','EstudianteController@planificacion');
Route::post('/proyecto/hitos','EstudianteController@show_hitos');
Route::post('/hitos_month','EstudianteController@hitos_month');

//Reunión
Route::post('/reunion/edit','ProfesorGuiaController@editreunion');
Route::post('/reunion/editar','ProfesorGuiaController@updatereunion');
Route::post('/reuniones_month','ProfesorGuiaController@reuniones_month');
Route::post('reunion/delete/{reunion}','ProfesorGuiaController@delete');

//Grafico
Route::get('chart/searchType', 'ChartController@searchType');
Route::get('chart/searchModule', 'ChartController@searchModule');
Route::get('chart/searchState', 'ChartController@searchState');
Route::get('chart/searchProfessor', 'ChartController@searchProfessor');
Route::post('chart', 'ChartController@plot');