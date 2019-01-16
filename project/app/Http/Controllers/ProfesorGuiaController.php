<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Proyecto;
use App\Persona;
use App\Estudiante;
use App\Reunion;
use Carbon\Carbon;
use App\User;
use App\InvitadoProyecto;
use App\Notificacion;
use App\Mail\NotificacionReunion;
use App\Matricula;

class ProfesorGuiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$proyectos = Proyecto::where('profesorGuia_id',"=",auth()->user()->id)->get();
        $usuarios= User::all();
        $invitados = array();
        foreach ($usuarios as $user) {
            if($user->roles->contains('id', 6))
            {
                array_push($invitados, $user);
            }
        }
        
        dd($proyectos);
    	return view('profesorguia.index',compact('proyectos','invitados'));
    }

    public function estudiantes()
    {
    	$proyectos = Proyecto::where('profesorGuia_id',"=",auth()->user()->id)->get();
    	$estudiantes = array();

    	foreach ($proyectos as $proyecto) {
            if($proyecto->estudiante_id != 0)
            {
                $persona = Estudiante::where('persona_id',"=",$proyecto->estudiante_id)->get();
                array_push($estudiantes,$persona);
            }
    		
    	}

        //dd($estudiantes);
    	return view('profesorguia.estudiantes',compact('proyectos'));
    }

    public function planificacion()
    {
        $proyectos = Proyecto::where('profesorGuia_id',"=",auth()->user()->id)->get();
        $personas = array();

        foreach ($proyectos as $proyecto) {
            if($proyecto->estudiante_id != 0)
            {
                $persona = Persona::find($proyecto->estudiante_id);
                array_push($personas,$persona);
            }
            
        }
        //dd($personas);
        // Cargar los estudiantes de los cuales el profesor es guia
        return view('profesorguia.planificacionreunion',compact('personas'));
    }

    public function createreunion(Request $request)
    {
        //dd($request);
        Reunion::create([
            'fecha' => Carbon::parse($request['fecha'])->format('Y/m/d'),
            'estudiante_id' => $request['estudiante_id'],
            'profesor_guia_id' => auth()->user()->id,
            'hora' => Carbon::parse($request['fecha'])->modify($request['hora'])
            
        ]);

        /* Crear Notificacion */
        $profesor = Persona::find(auth()->user()->id);
        $emailEstudiante = Persona::where('id',"=",$request['estudiante_id'])->first();
        $fecha= Carbon::parse($request['fecha'])->format('d/m/Y');
        Notificacion::create([
            'texto' => 'El Profesor '.$profesor->nombre().' agendo una reunión para el '.$fecha.' a las '.$request['hora'].' hrs.',
            'tipo_notificacion_id' => 5,
            'leido' => 0,
            'email' => $emailEstudiante->email,
            'entregable_id' => 0
        ]);

        /* Crear mensaje y enviar notificacion */
        $fecha= Carbon::parse($request['fecha'])->format('d/m/Y');
        $modificado = 0;
        Mail::to($emailEstudiante->email)->send(new NotificacionReunion($fecha,$request['hora'],$modificado));

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La reunion se ha registrado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
    }

    public function updatereunion(Request $request)
    {
        //dd($request);
        $fecha2 = Carbon::parse($request['fechaE'])->format('Y/m/d');

        $reunion = Reunion::find($request['reunion_id']);
        $reunion->fecha = $fecha2;
        $reunion->hora = Carbon::parse($fecha2)->modify($request['horaE']);
        $reunion->estudiante_id = $request['estudiante_idE'];
        $reunion->save();

        /* Crear Notificacion */
        $profesor = Persona::find(auth()->user()->id);
        $emailEstudiante = Persona::where('id',"=",$request['estudiante_idE'])->first();
        //dd($emailEstudiante->email);
        $fecha= Carbon::parse($request['fechaE'])->format('d/m/Y');
        Notificacion::create([
            'texto' => 'El Profesor '.$profesor->nombre().' a modificado la reunión para el '.$fecha.' a las '.$request['horaE'].' hrs.',
            'tipo_notificacion_id' => 5,
            'leido' => 0,
            'email' => $emailEstudiante->email,
            'entregable_id' => 0
        ]);

        /* Crear mensaje y enviar notificacion */
        $fecha= Carbon::parse($request['fechaE'])->format('d/m/Y');
        $modificado = 1;
        Mail::to($emailEstudiante->email)->send(new NotificacionReunion($fecha,$request['horaE'],$modificado));
        
        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La reunion se ha modificado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
    }

    public function show_reunion(Request $request)
    {
        $user = \Auth::user();
        $reuniones = Reunion::where('profesor_guia_id', $user->id)->get();
        $response =  array();
        foreach ($reuniones as $reunion)
        {
            $fecha= Carbon::parse($reunion->hora);
            $hora = $fecha->format('H:i');
            $object = array(
                'id' =>$reunion->id,
                'title' =>'Hora:'.$hora.' Estudiante:'. $reunion->persona->nombres.' '.$reunion->persona->apellidos,
                'start' => $reunion->fecha,
                'description'=>'Reunión a las '.$hora .'hrs.'//$reunion->hora
            );
            array_push($response, $object);
        }
        return Response()->json($response);
    }

    public function editreunion(Request $request)
    {
        $reunion = Reunion::find($request['id']);
        $fecha = Carbon::parse($reunion->hora);
        $reunion->hora = $fecha->format('H:i');
        $fechar = Carbon::parse($reunion->fecha);
        $reunion->fecha = $fechar->format('d-m-Y');
        return $reunion;
    }

    public function reuniones_month(Request $request)
    {        
        $coutn_month = 0;
        $user = \Auth::user();

        $reuniones = Reunion::where('profesor_guia_id', $user->id)->get();

        $mytime = Carbon::now();
        $month = $mytime->format('m');
        $year = $mytime->format('Y');

        foreach ($reuniones as $reunion)
        {
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

    public function delete(Reunion $reunion)
    {
        //dd($reunion);
        $estudiante = Persona::where('id',"=",$reunion->estudiante_id)->first();
        $profesor = Persona::where('id',"=",$reunion->profesor_guia_id)->first();
        //dd($emailEstudiante->email);
        $fecha= Carbon::parse($reunion->fecha)->format('d/m/Y');
        Notificacion::create([
            'texto' => 'El Profesor '.$profesor->nombre().' a cancelado la reunión del '.$fecha,
            'tipo_notificacion_id' => 5,
            'leido' => 0,
            'email' => $estudiante->email,
            'entregable_id' => 0
        ]);

        /* Crear mensaje y enviar notificacion */
        $modificado = 2;
        Mail::to($estudiante->email)->send(new NotificacionReunion($fecha,$fecha,$modificado));

        $reunion->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La reunión se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back(); 
    }

    public function addInvitado(Request $request, Proyecto $proyecto)
    {
        //dd($request,$proyecto);

        $invitadoProyecto = InvitadoProyecto::create([
            'persona_id' => $request['invitado_id'],
            'proyecto_id' => $proyecto->id
        ]);

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La asociación del proyecto con el invitado se ha agregado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back(); 
    }

    public function updateInvitado(Request $request)
    {
        $invitado = InvitadoProyecto::where('proyecto_id',"=",$request['id'])->first();
        return $invitado;
    }

    public function editInvitado(Request $request)
    {
        //dd($request);
        $invitadoProyecto = InvitadoProyecto::where('proyecto_id',"=",$request['proyecto_id'])->first();
        $invitadoProyecto->persona_id = $request['invitado_id'];

        $invitadoProyecto->save();
        
        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La asociación del proyecto con el invitado se ha modificado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();

    }

    public function removeInvitado(Proyecto $proyecto)
    {
        $invitadoProyecto = InvitadoProyecto::where('proyecto_id',"=",$proyecto->id)->first();
        $invitadoProyecto->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'La asociación del proyecto con el invitado se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back(); 
    }

    public function estudiante(Request $request)
    {

        $estudiante = Proyecto::where('estudiante_id',"=",$request['estudiante_id'])->first();
        return Response()->json($estudiante);
    } 

    public function editestudiante(Request $request)
    {
        //editar el proyecto y darle agregar a matricula
        //dd($request);

        Matricula::create([
            'year' => $request['year'],
            'semestre' => $request['semestre'],
            'estado' => $request['estado_id'],
            'estudiante_id' => $request['estudiante_id'],
            'curso_id' => $request['curso_id']
        ]);

        $proyecto = Proyecto::where('estudiante_id',"=",$request['estudiante_id'])->first();
        $proyecto->update($request->except ('_token'));

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El estado del estudiante se ha modificado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
    }

}
