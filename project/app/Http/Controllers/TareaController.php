<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Hito;
use App\Estudiante;
use App\User;
use App\Proyecto;
use App\Tarea;
use App\Entregable;
use App\Historial;
use App\Comentario;
use App\Notificacion;

class TareaController extends Controller
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
    	return view('tarea.index',compact('tareas'));
    }

    public function create()
    {
        $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->get();
    	$hitos = Hito::where('proyecto_id',"=",$proyecto[0]['id'])->get();
        return view('tarea.create',compact('hitos'));
    }

    public function store(Request $request)
    {

    	//dd($request);
        $validator = Validator::make($request->all(), [
    		'nombre' => 'required|string|min:3|max:100',
        ]);

        if ($validator->fails()) 
        {
        	session()->flash('title', '¡Error!');
        	session()->flash('message', 'Existieron errores!');
        	session()->flash('icon', 'fa-check');
        	session()->flash('type', 'danger');
            return redirect('tarea/create')->withErrors($validator)->withInput();
        }
       
        Tarea::create([
            'nombre' => $request['nombre'],
            'fecha_limite' => Carbon::parse($request['fecha_limite'])->format('Y-m-d'),
            'comentario' => $request['comentario'] ,
            'hito_id' => $request['hito_id']
        ]);

        // creo algo en el historial
        $hito = Hito::find($request['hito_id']);
        Historial::create([
            'texto' => 'Creo la tarea '. $request['nombre'] .' en el hito '. $hito->nombre,
            'estudiante_id' => auth()->user()->id
        ]);


        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La tarea se ha registrado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        //Redirigir a las tareas del hito

        return redirect('/hito/'.$request['hito_id'].'/info');        
    }

    public function info(Tarea $tarea)
    {
        if(auth()->user()->rol_id == 5)
        {
            $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->first();
            $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
            $tareas = array();
            $tareas_todas = Tarea::all();
            foreach ($tareas_todas as $tare) {
                foreach ($hitos as $hito) {
                    if($tare->hito_id == $hito->id)
                    {
                        array_push($tareas,$tare); 
                    }
                }
               
            }

            //dd($tarea->id);
            $count = 0;
            foreach ($tareas as $new_tarea) {
                //dd($new_tarea->id);
                if($new_tarea->id == $tarea->id)
                {
                    $count++;
                }
            }
            //dd($count);
            if($count > 0)
            {
                //dd($hito);
                //Buscar sus entregables
                $entregables = Entregable::where('tarea_id',"=",$tarea->id)->get();
                //mostrar sus datos
                return view('tarea.info',compact('tarea','entregables'));
            }
            else
            {
                $tipo = 2;
                return view('accesodenegado', compact('tipo'));
            } 
        }
        if(auth()->user()->rol_id == 3)
        {
            $proyectos = Proyecto::where('profesorGuia_id',"=",auth()->user()->id)->get();
            $hitos_Pro = array();
            $hitos_todas = Hito::all();
            foreach ($proyectos as $proy) {
                foreach ($hitos_todas as $hitto) {
                    if($proy->id == $hitto->proyecto_id)
                    {
                        array_push($hitos_Pro,$hitto); 
                    }
                }
               
            }
            //dd($hitos);
            $count = 0;
            $tareashito = Tarea::all();
            foreach ($hitos_Pro as $hit) {
                foreach ($tareashito as $tareita) {
                    if($hit->id == $tareita->hito_id && $tareita->id ==$tarea->id)
                    {
                        $count++;
                    }
                }
            }
            //dd($count);
            if($count > 0)
            {
                //dd($hito);
                //Buscar sus entregables
                $entregables = Entregable::where('tarea_id',"=",$tarea->id)->get();
                //mostrar sus datos
                return view('tarea.info',compact('tarea','entregables'));
            }
            else
            {
                $tipo = 2;
                return view('accesodenegado', compact('tipo'));
            } 
        }
          
        
    }

    public function delete(Tarea $tarea)
    {
        $entregables = Entregable::all();
        $comentarios = Comentario::all();
        $notificaciones = Notificacion::all();
        foreach ($entregables as $entregable) {
            if($entregable->tarea_id == $tarea->id)
            {
                foreach ($comentarios as $comentario) {
                    if($comentario->entregable_id == $entregable->id)
                    {
                        $comentario->delete();
                    }
                }
                foreach ($notificaciones as $notificacion) {
                    if($notificacion->entregable_id == $entregable->id)
                    {
                        $notificacion->delete();
                    }
                }

                $entregable->delete();
            }
        } 

        $hito = Hito::find($tarea->hito_id);
        Historial::create([
            'texto' => 'Elimino la tarea '. $tarea->nombre .' del hito '. $hito->nombre,
            'estudiante_id' => auth()->user()->id
        ]);
       
        $tarea->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La tarea '. $tarea->nombre.' se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/hito/'.$hito->id.'/info');
    }

    public function edit(Tarea $tarea)
    {
        $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->first();
        $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();

        //dd($hitos);
        $fecha = Carbon::parse($tarea->fecha_limite);
        $tarea->fecha_limite = $fecha->format('Y-m-d');

        $tareas = array();
        $tareas_todas = Tarea::all();
        foreach ($tareas_todas as $tare) {
            foreach ($hitos as $hito) {
                if($tare->hito_id == $hito->id)
                {
                    array_push($tareas,$tare); 
                }
            }
           
        }

        //dd($tarea->id);
        $count = 0;
        foreach ($tareas as $new_tarea) {
            //dd($new_tarea->id);
            if($new_tarea->id == $tarea->id)
            {
                $count++;
            }
        }
        //dd($count);
        if($count > 0)
        {
            foreach ($hitos as $hito) {
                if($hito->id == $tarea->hito_id)
                {
                    $fecha = Carbon::parse($hito->fecha_inicio);
                    $fechaT = Carbon::parse($hito->fecha_termino);
                    $fecha_inicio = $fecha->format('d-m-Y');
                    $fecha_termino = $fechaT->format('d-m-Y');
                    $fecha2 = $fecha->format('d-m-Y');
                    $fechaT2 = $fechaT->format('d-m-Y');
                    $tarea->fecha_limite = Carbon::parse($tarea->fecha_limite)->format('d-m-Y');
                } 
            }
            //dd($fechaT2);
            return view('tarea.edit', compact('hitos','tarea','fecha_inicio','fecha_termino','fecha2','fechaT2'));
        }
        else
        {
            $tipo = 2;
            return view('accesodenegado', compact('tipo'));
        }   
    }
    
    public function update(Request $request, $id)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
            'comentario' => 'required|string|min:3|max:200'
        ]);

        if ($validator->fails()) 
        {
            session()->flash('title', '¡Error!');
            session()->flash('message', 'Existieron errores!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'danger');
            return redirect('tarea/create')->withErrors($validator)->withInput();
        }
        $request['fecha_limite'] = Carbon::parse($request['fecha_limite'])->format('Y-m-d');
        $tarea = Tarea::find($id);
        //dd($request);
        $tarea->update($request->except ('_token'));

        $hito = Hito::find($request['hito_id']);
        Historial::create([
            'texto' => 'Modifico la tarea '. $tarea->nombre .' en el hito '. $hito->nombre,
            'estudiante_id' => auth()->user()->id
        ]);

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La tarea se ha editado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/hito/'.$request['hito_id'].'/info');    
    } 

    public function fechas(Request $request)
    {
        $hito_id = $request['hito_id'];
        $hito = Hito::find($hito_id);
        $fecha_inicio = Carbon::parse($hito->fecha_inicio);
        $fecha_termino = Carbon::parse($hito->fecha_termino);
        $hito->fecha_inicio = $fecha_inicio->format('d-m-Y');
        $hito->fecha_termino = $fecha_termino->format('d-m-Y');

        $fechaI = $fecha_inicio->format('d-m-Y');
        $fechaT = $fecha_termino->format('d-m-Y');
        return response()->json(array('fecha_inicio' => $hito->fecha_inicio,'fecha_termino' => $hito->fecha_termino,'fechaI' => $fechaI ,'fechaT' => $fechaT));
    }

}
