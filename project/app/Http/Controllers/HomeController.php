<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Persona;
use App\Proyecto;
use App\Hito;
use App\ProfesorCurso;
use App\Notificacion;
use App\Tarea;
use App\Entregable;
use App\InvitadoProyecto;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();

        if($user->login == 0)
        {
            return view('changepass',compact('user'));
        }

        elseif($user->roles->count() > 1 && $user->rol_id == 0 && $user->login == 1)
        {
            return view('pickrole', compact('user'));
        }

        else if($user->Administrador())
        {
            $personas = Persona::All();

            return view('persona.index', compact('personas'));
        }
        else if($user->Estudiante())
        {
            $proyecto = Proyecto::where('estudiante_id', $user->id)->first();
            //dd($proyecto);
            //ver cantidad de tareas con entregable si tiene proyecto
            if($proyecto!=null)
            {
                $estados = array();
                $count = 0;
                $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
                //dd($hitos);
                foreach ($hitos as $hito) {

                    $tareas = Tarea::where('hito_id',"=",$hito->id)->get();
                    foreach ($tareas as $tarea) {
                        $entregables = Entregable::where('tarea_id',"".$tarea->id)->get();
                        if(sizeof($entregables) > 0)
                        {
                            $count++;
                        }
                    }

                    //calculo
                    $total = 0;
                    $count_Tareas = sizeof($tareas);
                    if($count_Tareas){
                        $total = ($count*100)/$count_Tareas;
                    }

                    $hito_modificado = Hito::find($hito->id);
                    $hito->progreso = $total;
                    $hito->save();


                    $count = 0;
                }
                //dd($estados);
                return view('estudiante.index', compact('proyecto','estados'));
            }
            return view('Error404');
        }
        else if($user->Funcionario())
        {
            $proyectos = Proyecto::All();
            return view('funcionario.index',compact('proyectos'));
        }
        else if($user->ProfesorGuia())
        {
            $proyectos = Proyecto::where('profesorguia_id', $user->id)->get();
            $usuarios= User::all();
            $invitados = array();
            foreach ($usuarios as $user) {
                if($user->roles->contains('id', 6))
                {
                    array_push($invitados, $user);
                }
            }
        
            //dd($invitados);
            return view('profesorguia.index', compact('proyectos','invitados'));
        }
        else if($user->profesorCurso())
        {
            $curso = ProfesorCurso::where('profesor_id', $user->id)->first();
            $proyectos = Proyecto::where('estado_id',$curso->curso_id)->get();
            if($curso->curso_id == 1)
            {
                $name_Curso = "Formulación de Proyecto de Titulación";
            }
            else
            {
                $name_Curso = "Proyecto de Tìtulación";
            }
            //dd($proyectos);
            return view('profesorcurso.index',compact('proyectos','name_Curso'));
        }
        else if($user->invitado())
        {
            //Buscar a que proyect esta asociado
            $proyectosmios = InvitadoProyecto::where('persona_id',"=",$user->id)->get();
            //$proyecto = Proyecto::find($proyecto_id->proyecto_id);
            //dd($proyectosmios);

            $proyectos_todos = Proyecto::all();
            $proyectos = array();
            foreach ($proyectos_todos as $proyecto) {
                foreach ($proyectosmios as $myproyecto) {
                    if($myproyecto->proyecto_id == $proyecto->id)
                    {
                        array_push($proyectos,$proyecto);
                    }
                }
            }
            //dd($proyectos);
            if($proyectos != null)
            {
                return view('invitado.index',compact('proyectos'));
            }
            else
            {
                return view('Error404');
            }
            
        }

        else
        {
            return view();
        }
    }

    public function pick()
    {
        $user = \Auth::user();

        if($user->roles->count() > 1)
        {
            return View('pickrole', compact('user'));
        }
        else
        {
            return redirect('/');
        }
    }

    public function pickRole(Request $request)
    {
        $rol = $request['rol'];

        $user = User::find($request['user']);
        $user->rol_id = $rol;
        $user->save();

        return redirect('/');
    }

    public function changepass(Request $request)
    {
        //dd($request);

        if($request['password1'] == $request['password2'])
        {
            $user = User::find($request['user_id']);
            $user->login = 1;
            $user->password = bcrypt($request['password2']);
            $user->save();

            return redirect('/');

        }
        else
        {
            session()->flash('title', '¡Error!');
            session()->flash('message', 'Las contraseñas no coinciden!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'danger');

            return back();
        }
    }
}
