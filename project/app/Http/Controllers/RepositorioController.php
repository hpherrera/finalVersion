<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Hito;
use App\Estudiante;
use App\User;
use App\Proyecto;
use App\Tarea;
use App\Entregable;
class RepositorioController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->get();
        $hitos = Hito::where('proyecto_id',"=",$proyecto[0]['id'])->get();
        $tareas = array();
        $tareas_todas = Tarea::all();
        foreach ($tareas_todas as $tarea) {
            foreach ($hitos as $hito) {
                if($tarea->hito_id == $hito->id)
                {
                    array_push($tareas,$tarea); 
                }
            }
           
        }

        $entregables_proyecto = Entregable::all();
        $entregables = array();
        foreach ($tareas as $tarea) {
            foreach ($entregables_proyecto as $entregable) {
                if($entregable->tarea_id == $tarea->id)
                {
                    array_push($entregables,$entregable);
                }
            }
        }
        
    	return view('repositorio.index',compact('entregables'));
    }
}
