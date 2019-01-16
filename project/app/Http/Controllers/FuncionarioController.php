<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\TipoProyecto;
use App\EstadoProyecto;
use App\Estudiante;
use App\Area;
use App\Persona;
use App\User;
use App\Hito;
use App\Year;
use App\Entregable;
use App\Tarea;
use App\Matricula;


class FuncionarioController extends Controller
{

    public function index()
    {
        $proyectos = Proyecto::All();
        return view('funcionario.index',compact('proyectos'));
    }
    public function create()
    {
    	$personas = array();
        $estudiantes = Estudiante::all();
        foreach ($estudiantes as $estudiante) {
            if($estudiante->ocupado == 0)
            {
                $persona = User::find($estudiante->persona_id);
                array_push($personas, $persona);
            }
        }
        $years = Year::all();
        $tipos = TipoProyecto::all();
        $estados = EstadoProyecto::all();
        $areas = Area::all();

        //dd($personas);
        return view('Funcionario.createoldproyect', compact('personas','tipos','estados','areas','years'));
    }

    public function all_students()
    {

        if(auth()->user()->rol_id == 2)
        {
            $personas = array();
            $estudiantes = Estudiante::all();
            foreach ($estudiantes as $estudiante) {
                $persona = Persona::find($estudiante->persona_id);
                array_push($personas, $persona);
            }
            $proyectos = Proyecto::all();
            return view('Funcionario.estudents',compact('personas','proyectos'));
        }
        else
        {
            $tipo = 3;
            return view('accesodenegado', compact('tipo'));
        }
    }

    public function students()
    {

        if(auth()->user()->rol_id == 2)
        {
            $personas = array();
            $estudiantes = Estudiante::all();
            foreach ($estudiantes as $estudiante) {
                $persona = Persona::find($estudiante->persona_id);
                array_push($personas, $persona);
            }
            $proyectos = Proyecto::all();
            return view('Funcionario.estudentsegresados',compact('personas','proyectos'));
        }
        else
        {
            $tipo = 3;
            return view('accesodenegado', compact('tipo'));
        }
    }

    public function stadistic()
    {
        return view('Funcionario.stadistic');
    }

    public function proyectoinfo(Proyecto $proyecto)
    {

        if(auth()->user()->rol_id == 2)
        {
            $estudiante = Persona::where('id',"=",$proyecto->estudiante_id)->first();
            //buscar documento final
            //dd($estudiante);
            $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
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
            

            $entregables = array();
            $entregables_todos = Entregable::all();
            foreach ($entregables_todos as $entregable) {
                foreach ($tareas as $tarea) {
                    if($entregable->tarea_id == $tarea->id && $entregable->subidoPor == 5 && $entregable->tipo == 2)
                    {
                        array_push($entregables,$entregable); 
                    }
                }
               
            }

            $documentofinal = collect($entregables)->last();
            return view('estudiante.informacionproyecto',compact('estudiante','proyecto','documentofinal'));
        }
        else
        {
            $tipo = 3;
            return view('accesodenegado', compact('tipo'));
        }
    }

    public function promedio_egreso()
    {
        $estudiantes_egresados = array();

        $proyectos = Proyecto::all();

        foreach ($proyectos as $proyecto) {
            if($proyecto->estado_id == 3)
            {
                //dd($proyecto->estudiante_id);
                $estudiante = Matricula::where('estudiante_id',"=",$proyecto->estudiante_id)->get();
                //dd($estudiante->first());
                //Inicio
                $inicio = $estudiante->first();
                //Termino
                $termino = $estudiante->last();
                //Nombre Estudiante
                $nombre = Persona::find($proyecto->estudiante_id);
                //dd($nombre);
                $object = array(
                    'nombre' => $nombre->nombres.' '.$nombre->apellidos,
                    'asi' => $inicio->semestre.' / '.$inicio->year,
                    'ast' => $termino->semestre.' / '.$termino->year,
                    'contador' => $estudiante->count() - 1
                );

                //dd($object);
                array_push($estudiantes_egresados, $object);
            }
        }

        //promedio general suma de contador dividido en count estudiantes egresados
        $contador = 0;
        foreach ($estudiantes_egresados as $e) {
            $contador = $contador + $e['contador'];
        }

        if(sizeof($estudiantes_egresados) > 0)
        {
            $promedio = $contador/sizeof($estudiantes_egresados);
        }
        else
        {
            $promedio = 0;
        }
        
        return view('funcionario.promedioduracion', compact('promedio','estudiantes_egresados'));
    }
}
