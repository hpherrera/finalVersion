<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comentario;
use App\User;
use App\Persona;
use App\Entregable;
use App\Tarea;
use App\Hito;
use App\Proyecto;
use App\InvitadoProyecto;
use App\Notificacion;

class ComentarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
    	//dd($request);
    	$persona = Persona::where('email',"=",auth()->user()->email)->get();
        $nombre_persona = $persona[0]['nombres']." ".$persona[0]['apellidos'];

    	$comentario = Comentario::create([
            'texto' => $request['comentarionuevo'],
            'entregable_id' => $request['entregablePadre'],
            'user_id' => auth()->user()->id,
            'user_name' => $nombre_persona
        ]);

        $entregable = Entregable::find($request['entregablePadre']);

        $tareas = Tarea::all();

        foreach ($tareas as $tarea) {
            if($tarea->id == $entregable->tarea_id)
            {
                $hito = Hito::where('id',"=",$tarea->hito_id)->first();
                $proyecto = Proyecto::where('id',"=",$hito->proyecto_id)->first();
            }
        }

        $estudiante = Persona::where('id',"=",$proyecto->estudiante_id)->first();
        $profesor = Persona::where('id',"=",$proyecto->profesorGuia_id)->first();
        $invitado = InvitadoProyecto::where('proyecto_id',"=",$proyecto->id)->first();
        if($invitado != null)
        {
            $emailInvitado = Persona::where('id',"=",$invitado->persona_id)->first();
        }
        
        //dd(auth()->user()->rol_id,$estudiante->email,$profesor->email,$emailInvitado->email);
        
        if(auth()->user()->rol_id == 3)
        {

            Notificacion::create([
                'texto' => 'El profesor guía '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $estudiante->email,
                'entregable_id' => $request['entregablePadre']
            ]);
            if($invitado != null)
            {
                Notificacion::create([
                    'texto' => 'El profesor guía '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                    'tipo_notificacion_id' => 4,
                    'leido' => 0,
                    'email' => $emailInvitado->email,
                    'entregable_id' => $request['entregablePadre']
                ]);
            }
        }
        if(auth()->user()->rol_id == 5)
        {
            Notificacion::create([
                'texto' =>  'El estudiante '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $profesor->email,
                'entregable_id' => $request['entregablePadre']
            ]);

            if($invitado != null)
            {
                Notificacion::create([
                    'texto' => 'El estudiante '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                    'tipo_notificacion_id' => 4,
                    'leido' => 0,
                    'email' => $emailInvitado->email,
                    'entregable_id' => $request['entregablePadre']
                ]);
            }
        }
        if(auth()->user()->rol_id == 6)
        {
            Notificacion::create([
                'texto' =>  'El invitado '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $estudiante->email,
                'entregable_id' => $request['entregablePadre']
            ]);
            Notificacion::create([
                'texto' => 'El invitado '.$nombre_persona.' comento en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $profesor->email,
                'entregable_id' => $request['entregablePadre']
            ]);
        }
        

    	session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El comentario se ha agregado exitosamente');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
    }

    public function edit(Request $request,$id)
    {
        $persona = Persona::where('email',"=",auth()->user()->email)->get();
        $nombre_persona = $persona[0]['nombres']." ".$persona[0]['apellidos'];
    	//dd($request);
		$comentario = Comentario::find($id);
		$comentario->texto = $request['comentarioEdit'];

		$comentario->save();

        $entregable = Entregable::find($request['entregablePadre']);

        $tareas = Tarea::all();

        foreach ($tareas as $tarea) {
            if($tarea->id == $entregable->tarea_id)
            {
                $hito = Hito::where('id',"=",$tarea->hito_id)->first();
                $proyecto = Proyecto::where('id',"=",$hito->proyecto_id)->first();
            }
        }

        $estudiante = Persona::where('id',"=",$proyecto->estudiante_id)->first();
        $profesor = Persona::where('id',"=",$proyecto->profesorGuia_id)->first();
        $invitado = InvitadoProyecto::where('proyecto_id',"=",$proyecto->id)->first();
        $emailInvitado = Persona::where('id',"=",$invitado->persona_id)->first();
        //dd(auth()->user()->rol_id,$estudiante->email,$profesor->email,$emailInvitado->email);

        if(auth()->user()->rol_id == 3)
        {

            Notificacion::create([
                'texto' => 'El profesor guía '.$nombre_persona.' edito un comentario en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $estudiante->email,
                'entregable_id' => $request['entregablePadre']
            ]);
            Notificacion::create([
                'texto' => 'El profesor guía '.$nombre_persona.' edito un comentario en el estregable'.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $emailInvitado->email,
                'entregable_id' => $request['entregablePadre']
            ]);
        }
        if(auth()->user()->rol_id == 5)
        {
            Notificacion::create([
                'texto' =>  'El estudiante '.$nombre_persona.' edito un comentario en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $profesor->email,
                'entregable_id' => $request['entregablePadre']
            ]);
            Notificacion::create([
                'texto' => 'El estudiante '.$nombre_persona.' edito un comentario en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $emailInvitado->email,
                'entregable_id' => $request['entregablePadre']
            ]);
        }
        if(auth()->user()->rol_id == 6)
        {
            Notificacion::create([
                'texto' =>  'El invitado '.$nombre_persona.' edito un comentario en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $estudiante->email,
                'entregable_id' => $request['entregablePadre']
            ]);
            Notificacion::create([
                'texto' => 'El invitado '.$nombre_persona.' edito un comentario en el estregable '.$entregable->nombre,
                'tipo_notificacion_id' => 4,
                'leido' => 0,
                'email' => $profesor->email,
                'entregable_id' => $request['entregablePadre']
            ]);
        }

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El comnetario se ha modificado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
        
    }

    public function delete(Comentario $comentario)
    {
    	//dd($comentario);
    	$comentario->delete();

    	session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El comentario se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return back();
    }

}
