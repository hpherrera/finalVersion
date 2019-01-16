<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Proyecto;
use App\TipoProyecto;
use App\EstadoProyecto;
use App\Estudiante;
use App\Area;
use App\Persona;
use App\User;
use App\Hito;
use App\Year;
use App\Curso;
use App\Matricula;

class ProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$proyectos = Proyecto::all();

    	return view('proyecto.index',compact('proyectos'));
    }

    public function create()
    {
    	$personas = array();
        $years = Year::all();
        $estudiantes = Estudiante::all();
        //dd($estudiantes);
        foreach ($estudiantes as $estudiante) {
            if($estudiante->ocupado == 0)
            {
                //dd($estudiante);
                $persona = Persona::find($estudiante->persona_id);
                array_push($personas, $persona);
            }
        }
        //dd($personas);
        $tipos = TipoProyecto::all();
        $estados = EstadoProyecto::all();
        $areas = Area::all();

        //dd($personas);
        return view('proyecto.create', compact('personas','tipos','estados','areas','years'));
    }

    public function store(Request $request)
    {
        //dd($request);
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|min:3|max:100',
            'estudiante_id' => 'required|numeric',
            'tipo_id' => 'required|numeric',
            'estado_id' => 'required|numeric',
            'area_id' => 'required|numeric'
        ]);

        //dd($request);

        if ($validator->fails()) 
        {
        	session()->flash('title', '¡Error!');
        	session()->flash('message', 'Existieron errores!');
        	session()->flash('icon', 'fa-check');
        	session()->flash('type', 'danger');
            return redirect('proyecto/create')->withErrors($validator)->withInput();
        }

        Proyecto::create([
            'titulo' => $request['titulo'],
            'estudiante_id' => $request['estudiante_id'],
            'tipo_id' => $request['tipo_id'],
            'estado_id' => $request['estado_id'],
            'progreso' => 0,
            'area_id' => $request['area_id'],
            'profesorGuia_id' => auth()->user()->id,
            'year' => $request['year'],
            'semestre' => $request['semestre'],
            'nombre_estudiante' => $request['nombre_estudiante'],
            'curso_id' => $request['curso_id']
        ]);

        Matricula::create([
            'year' => $request['year'],
            'semestre' => $request['semestre'],
            'estado' => $request['estado_id'],
            'estudiante_id' => $request['estudiante_id'],
            'curso_id' => $request['curso_id']
        ]);

        if($request['estudiante_id'] != 0)
        {
            $estudiante = Estudiante::where('persona_id',"=",$request['estudiante_id'])->get();
            $estudiante[0]['ocupado'] = 1;
            $estudiante[0]->save();
        }
        
        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El proyecto se ha registrado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/');        
    }

    public function edit(Proyecto $proyecto)
    {
    	$estudiantes = User::where('rol_id',"=",5)->get();
        $tipos = TipoProyecto::all();
        $estados = EstadoProyecto::all();
        $areas = Area::all();
        $cursos = Curso::all();

        //dd($proyecto);
        return view('proyecto.edit', compact('proyecto','estudiantes','tipos','estados','areas','cursos'));
    }

    public function update(Request $request, $id)
    {
       //dd($request);
       $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|min:3|max:100',
            'tipo_id' => 'required|numeric',
            'estado_id' => 'required|numeric',
            'area_id' => 'required|numeric',
            'estudiante_id' => 'required|numeric'
        ]);


        if ($validator->fails()) 
        {
        	session()->flash('title', '¡Éxito!');
        	session()->flash('message', 'Existieron errores!');
        	session()->flash('icon', 'fa-check');
        	session()->flash('type', 'danger');
            return redirect('proyecto/edit')->withErrors($validator)->withInput();
        }


        // si cambia de curso notificar al estudiante

        $proyecto = Proyecto::find($id);
        $proyecto->update($request->except ('_token'));

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El proyecto se ha modificado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/');
    }

    public function delete(Proyecto $proyecto)
    {
        $proyecto->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El proyectp se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/'); 
    }

    public function info(Proyecto $proyecto)
    {
        $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
        return view('proyecto.info',compact('hitos','proyecto')); 
    }


}
