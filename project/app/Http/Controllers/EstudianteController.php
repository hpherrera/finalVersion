<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Estudiante;
use App\Historial;
use App\Persona;
use App\User;
use App\Proyecto;
use App\Hito;
use Carbon\Carbon;
use App\Tarea;
use App\Reunion;

class EstudianteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('estudiante.create');
    }

    public function historial(Proyecto $proyecto)
    {
        $historials = Historial::where('estudiante_id',$proyecto->estudiante_id)->get();
        return view('estudiante.historial',compact('historials'));
    }

    public function planificacion()
    {
        return view('estudiante.planificacion');
    }

    public function show_hitos(Request $request)
    {        
        $user = \Auth::user();

        $proyecto = Proyecto::where('estudiante_id', $user->id)->first();
        $hitos = Hito::where('proyecto_id',"=",$proyecto['id'])->get();

        $response = array();
        foreach ($hitos as $hito)
        {
            $object = array(
                'id' =>"",
                'title' => $hito->nombre,
                'start' => $hito->fecha_termino,
                'description'=>'Fecha limite para terminar este hito.',
                'color' => '#F39C12',  
                'textColor' => 'white'
            );
            array_push($response, $object);
        }
        $tareas_todas = Tarea::all();
        foreach ($tareas_todas as $tarea) {
            foreach ($hitos as $hito) {
                if($tarea->hito_id == $hito->id)
                {
                    $object = array(
                    'id' =>"",
                    'title' => $tarea->nombre,
                    'start' => $tarea->fecha_limite,
                    'description'=>'Tarea del hito "'.$hito->nombre.'" .',
                    'color' => '#00A65A',  
                    'textColor' => 'white'
                    );

                    array_push($response, $object);
                }
            }
           
        }

        $reuniones = Reunion::where('estudiante_id',"=",auth()->user()->id)->get();
        foreach ($reuniones as $reunion) {
            $reunion_hora = Carbon::parse($reunion->hora);
            $hora = $reunion_hora->format('H:i');
            $object = array(
            'id' =>"",
            'title' => "Reunión",
            'start' => $reunion->fecha,
            'description'=>'Reunión con profesor guía a las '.$hora,
            'color' => '#DD4B39',  
            'textColor' => 'white'
            );
            array_push($response, $object);

        }
 
        return Response()->json($response);
    }

    public function hitos_month(Request $request)
    {        
        $coutn_month = 0;
        $user = \Auth::user();

        $proyecto = Proyecto::where('estudiante_id', $user->id)->first();
        $hitos = Hito::where('proyecto_id',"=",$proyecto['id'])->get();

        $mytime = Carbon::now();
        $month = $mytime->format('m');
        $year = $mytime->format('Y');

        foreach ($hitos as $hito)
        {
            $lastDate = Carbon::parse($hito->fecha_termino);
            $lastDate_month= $lastDate->format('m');
            $lastDate_year= $lastDate->format('Y');
            if($lastDate_month == $month && $lastDate_year == $year)
            {
                $coutn_month++;
            }
            $tareas = Tarea::where('hito_id',"=",$hito->id)->get();
            foreach ($tareas as $tarea) {
                $lastDate = Carbon::parse($tarea->fecha_limite);
                $lastDate_month= $lastDate->format('m');
                $lastDate_year= $lastDate->format('Y');
                if($lastDate_month == $month && $lastDate_year == $year)
                {
                    $coutn_month++;
                }
            }
            
        }

        $reuniones = Reunion::where('estudiante_id',"=",auth()->user()->id)->get();
        foreach ($reuniones as $reunion) {
            $lastDate = Carbon::parse($reunion->fecha);
            $lastDate_month= $lastDate->format('m');
            $lastDate_year= $lastDate->format('Y');
            if($lastDate_month == $month && $lastDate_year == $year)
            {
                $coutn_month++;
            }
        }
        
        return $coutn_month;
    }

    

}
