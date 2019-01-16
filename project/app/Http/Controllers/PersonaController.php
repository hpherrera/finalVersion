<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Persona;
use App\Rol;
use App\TipoInvitado;
use App\User;
use App\Estudiante;
use App\Invitado;
use App\Mail\CrearCuenta;
use App\Curso;
use App\ProfesorCurso;

class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$personas = Persona::all();
        //dd($personas);
    	return view('persona.index',compact('personas'));
    }

    public function create2()
    {
        return view('estudiante.create');
    }

    public function create()
    {
        $roles = Rol::all();
        $tipos = TipoInvitado::all();
        $cursos = Curso::All();

        return view('persona.create', compact('roles','tipos','cursos'));
    }

    public function store(Request $request)
    {   
        $roles = $request['rol_id'];
       // dd($roles);
        $no_puede = 0;
        foreach ($roles as $rol) {
            if($rol == 5)
            {
                $no_puede++;
            }
        }
        if ($no_puede == 1 && Sizeof($request['rol_id'])>=2) 
        {
            session()->flash('title', '¡Error!');
            session()->flash('message', 'Existieron errores, el estudiante no puede tener otro rol en el sistema.');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'danger');
            return redirect('/create');
        }

        if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3 || auth()->user()->rol_id == 4)
        {
            //dd($request);
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|min:3|max:100',
                'apellidos' => 'required|string|min:3|max:100',
                'email' => 'required|string|email|max:255|unique:users',
            ]);


            if ($validator->fails()) 
            {
                session()->flash('title', '¡Error!');
                session()->flash('message', 'Existieron errores!');
                session()->flash('icon', 'fa-check');
                session()->flash('type', 'danger');

                if($request['profesor'] == 1)
                {
                    return redirect('/estudiantecreate');
                }
                elseif ($request['profesor'] == 2)
                {
                    return redirect('/estudiantecreate');
                }
                else
                {
                    return redirect('/create')->withErrors($validator)->withInput();
                }
            }

            $roles = $request['rol_id'];
            //dd($roles[0]);
            if(sizeof($roles) == 1)
            {
                $rolunico = (int)$roles[0];
            }
            else{
                $rolunico = 0;
            }

            //genero password
            $longitud = 8;
            $pass = substr(MD5(rand(5, 100)), 0, $longitud);

            $iduser = User::create([
                'email' => $request['email'],
                'password' => bcrypt($pass),// Generar aleatoriamente una password
                'rol_id' => $rolunico,
                'login' => 0
            ]);


            $id = Persona::create([
                'nombres' => $request['nombre'],
                'apellidos' => $request['apellidos'],
                'email' => $request['email']
            ]);

            foreach ($roles as $rol ) {
                /* Guardar los roles en la base de datos con user_roles*/

                $user_rol = Rol::find($rol);
                $user_rol->users()->attach((int)$iduser['id']);

                if($rol == 4)
                {
                    ProfesorCurso::create([
                        'curso_id' => $request['curso_id'],
                        'profesor_id'=>(int)$id['id']
                    ]);

                }

                if($rol == 5)
                {
                    Estudiante::create([
                        'matricula' => $request['matricula'],
                        'persona_id' =>(int)$id['id'],
                        'ocupado' => 0
                    ]);

                }

                if($rol == 6)
                {

                    if($request['tipo_id'] == 1)
                    {
                        Invitado::create([
                            'nombre' => $request['carrera'],
                            'persona_id' =>(int)$id['id'],
                            'tipo' => 1
                        ]);
                    }

                    if($request['tipo_id'] == 2)
                    {
                        Invitado::create([
                            'nombre' => $request['empresa'],
                            'persona_id' =>(int)$id['id'],
                            'tipo' => 2
                        ]);
                    }

                    if($request['tipo_id'] == 3)
                    {
                        Invitado::create([
                            'nombre' => "OTRO",
                            'persona_id' =>(int)$id['id'],
                            'tipo' => 3
                        ]);
                    }

                }
            }

            //ENVIAR CORREO DE CREACION DE CUENTA
            //$passwordIngreso = 123456;
            Mail::to($request['email'])->send(new CrearCuenta($iduser,$pass));
            
            
            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El usuario se ha registrado exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');

            //dd($request);
            if($request['profesor'] == 1)
            {
                return redirect('/estudiantes');
            }
            elseif ($request['profesor'] == 2)
            {
                return redirect('/index');
            }
            else
            {
                return redirect('/');        
            }
        }
        else
        {
            $tipo = 3;
            return view('accesodenegado', compact('tipo'));
        }
        
    }

    public function edit(Persona $persona)
    {
        if(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3 || auth()->user()->rol_id == 1)
        {
            $roles = Rol::all();
            $user = User::where('email',"=",$persona->email)->first();
            $tipos = TipoInvitado::all();
            $cursos = Curso::All();
            $roles_persona = $user->roles;
            $tipoInvitado = -1;
            if($user->roles->contains('id', 4)) // profesor curso
            {
                $curso = ProfesorCurso::where('profesor_id', "=", $persona->id)->first();
                //dd($curso);
                $curso_id = Curso::find($curso->curso_id);
                //dd($curso_id);
                $roles_persona = $user->roles;

                return view('persona.edit', compact('persona','roles','tipos','tipoInvitado','curso_id','cursos','roles_persona'));
            }
           
            if($user->roles->contains('id', 5))
            {
                //dd($persona->id);
                $estudiante = Estudiante::where('persona_id',"=",$persona->id)->first();
                //dd($estudiante);
                $matricula = $estudiante->matricula;
                //dd($matricula);
                $tipoInvitado = -1;
                $roles_persona = $user->roles;
                return view('persona.edit', compact('persona','roles','tipos','matricula','tipoInvitado','cursos','roles_persona'));
        
            }

            if($user->roles->contains('id', 6))
            {
                $invitado = Invitado::where('persona_id',"=",$persona->id)->first();
                $tipoInvitado = $invitado->tipo;
                $nombreInvitado = $invitado->nombre;
                //dd($tipoInvitado);
                $roles_persona = $user->roles;
                return view('persona.edit', compact('persona','roles','tipos','tipoInvitado','nombreInvitado','cursos','roles_persona'));
        
            }

            return view('persona.edit', compact('persona','roles','tipos','tipoInvitado','cursos','roles_persona'));
        }
        else
        {
            $tipo = 3;
            return view('accesodenegado', compact('tipo'));
        }
    }


    public function update(Request $request , $email)
    {
        $roles = $request['rol_id'];
        $no_puede = 0;
        foreach ($roles as $rol) {
            if($rol == 5)
            {
                $no_puede++;
            }
        }
        if ($no_puede == 1 && Sizeof($request['rol_id'])>=2) 
        {
            session()->flash('title', '¡Error!');
            session()->flash('message', 'Existieron errores, el estudiante no puede tener otro rol en el sistema.');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'danger');
            return back();
        }

        //Elimino la lista de roles que tiene el usuario
        $persona = Persona::where('email',"=",$email)->first();
        //dd($request);
        $roles = $request['rol_id'];
        $rolesTodos = [1,2,3,4,5,6];
        foreach ($rolesTodos as $rol) {

            //dd($rol);
            $user_rol = Rol::find($rol);

            $user_rol->users()->detach($persona->id);
        }
        //buscar los roles del loco y eliminarlos pero como no lo se tabla intermedia
        //$user_rol->users()->detach($user->id)

        //dd($request);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
            'apellidos' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:255|',
        ]);


        if ($validator->fails()) 
        {
            session()->flash('title', '¡Error!');
            session()->flash('message', 'Existieron errores!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'danger');
            return redirect('/create')->withErrors($validator)->withInput();
        }

        $persona->nombres = $request['nombre'];
        $persona->apellidos= $request['apellidos'];
        $persona->save();

        foreach ($roles as $rol ) {
            //Modificar los roles en la base de datos con user_roles
            $user_rol = Rol::find($rol);
            $user_rol->users()->attach($persona->id);

            if($rol == 4)
            {
                //dd($request['curso_id']);
                $profesor = ProfesorCurso::where('profesor_id',"=",$persona->id)->first();
                $profesor->curso_id= $request['curso_id'];
                $profesor->save();
            }

            if($rol == 5)
            {
                $estudiante = Estudiante::where('persona_id',"=",$persona->id)->first();
                $estudiante->matricula = $request['matricula'];
                $estudiante->save();
            }

            if($rol == 6)
            {

                //dd($request);
                if($request['tipo_id'] == 1)
                {
                    $invitado = Invitado::where('persona_id',"=",$persona->id)->first();   
                    $invitado->nombre = $request['carrera'];
                    $invitado->tipo= 1;
                    $invitado->save();
                }

                if($request['tipo_id'] == 2)
                {
                    $invitado = Invitado::where('persona_id',"=",$persona->id)->first();   
                    $invitado->nombre = $request['empresa'];
                    $invitado->tipo= 2;
                    $invitado->save();
                }

                if($request['tipo_id'] == 3)
                {
                    $invitado = Invitado::where('persona_id',"=",$persona->id)->first();   
                    $invitado->nombre = $request['OTRO'];
                    $invitado->tipo= 3;
                    $invitado->save();
                }

            }
        }

        

        //Validar quien fue
        //dd($request);
        if(auth()->user()->rol_id == 3)
        {
            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El estudiante se ha modificado exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');
            return redirect('/estudiantes');
        }
        elseif (auth()->user()->rol_id == 4)
        {
            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El estudiante se ha modificado exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');
            return redirect('/index');
        }
        else
        {
            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El usuario se ha modificado exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');
            return redirect('/');        
        }

    }

    public function delete(Persona $persona)
    {
        //dd($persona);
        $user = User::where('email',"=",$persona->email)->get();
        $persona->delete();
        $user[0]->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El usuario se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/index'); 
    }

}
