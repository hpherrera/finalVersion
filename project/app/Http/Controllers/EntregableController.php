<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Hito;
use App\Estudiante;
use App\User;
use App\Proyecto;
use App\Tarea;
use App\Entregable;
use App\Documento;
use App\Notificacion;
use App\Persona;
use Storage;
use File;
use App\Mail\NotificacionTarea;
use App\Mail\NotificacionRevisionEntregable;
use App\Comentario;
use App\TipoDocumento;

class EntregableController extends Controller
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

        return view('entregable.index',compact('entregables'));
    }

    public function create(Tarea $tarea)
    {
        /* Veo si es mi tarea */
        $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->first();
        $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
        $tipos_entregable = TipoDocumento::all();
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
        /* //////////////////// */
        if($count == 0)
        {
            $tipo = 2;
            return view('accesodenegado', compact('tipo'));
        }    
        else
        {
            return view('entregable.create',compact('tarea','tipos_entregable'));
        }
    }

    public function create2()
    {
        $today = Carbon::now();
        $today_user_day = $today->format('d');
        $today_user_month = $today->format('m');
        $today_user_y = $today->format('Y');
        $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->get();
        $hitos = Hito::where('proyecto_id',"=",$proyecto[0]['id'])->get();
        $tareas = array();
        $tareas_todas = Tarea::all();
        $tipos_entregable = TipoDocumento::all();
        foreach ($tareas_todas as $tarea) {

            $lastDateTarea = Carbon::parse($tarea->fecha_limite);
            $lastDate_tarea = $lastDateTarea->format('d');
            $last_tarea_month = $lastDateTarea->format('m');
            $last_tarea_y = $lastDateTarea->format('Y');

            if($last_tarea_month >= $today_user_month &&
                $last_tarea_y >= $today_user_y &&
                $lastDate_tarea >= $today_user_day)
            {
                foreach ($hitos as $hito) {
                    if($hito->id == $tarea->hito_id)
                    {
                        array_push($tareas,$tarea); 
                    }
                    
                }
            }
        }
        if(sizeof($tareas) > 0)
        {
            return view('entregable.create2',compact('tareas','tipos_entregable'));
        }
        else
        {
            session()->flash('title', '¡Aviso!');
            session()->flash('message', 'No se pueden subir entregables, ya que ninguna de sus tareas creadas tiene como fecha de termino posterior al día de hoy.');
            session()->flash('icon', 'fa-remove');
            session()->flash('type', 'warning');

            $proyecto = Proyecto::where('estudiante_id', auth()->user()->id)->first();
            return view('estudiante.index',compact('proyecto'));
        }  
    }

    public function store(Request $request)
    {

        //dd($request);
        /* obtengo el archivo PDF */
        $file = $request->file('archivo');

        /* Asigno un nombre unico al archivo Pdf */
        $uniqueFileName =  $request['nombre'].'_'.uniqid().'.' . $file->getClientOriginalExtension() ;

        if($file->getClientOriginalExtension() != 'pdf')
        {
            //Mensaje
            session()->flash('title', '¡Error!');
            session()->flash('message', 'El documento no es del formato solicitado!');
            session()->flash('icon', 'fa-remove');
            session()->flash('type', 'danger');
            return redirect('entregable/create2');
        }
        else
        {
            /* Creo  la Carpeta en orden de hito a tarea /ID_HITO/ID_TAREA y guardo en servidor */
            $carpeta = $request->hito."/".$request->tarea_id;
            $file->move(storage_path('archivos/'.$carpeta) , $uniqueFileName);


            /* Creo el entregable para enviarlo a base de datos */
            $carbon = new Carbon();
            $entregable = Entregable::create([
                'nombre' => $request['nombre'],
                'fecha' => $carbon->now(),
                'tarea_id' => $request['tarea_id'] ,
                'estadoEntregable_id' => 4,
                'ruta' => $carpeta."/".$uniqueFileName,
                'id_padre' => 0,
                'subidoPor' => 5,
                'tipo' =>$request['tipo_id']
            ]);

            //Mensaje
            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El documento se a subido exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');

            /* Buscar Profesor Guia para obtener su eamil */
            $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->get();
            $profesorGuia = User::find($proyecto[0]['profesorGuia_id']);
            $emailProfesorGuia = $profesorGuia->email;

            /* email Estudiante */
            $eamilEstudiante = auth()->user()->email;

            /* Crear Notificacion */
            $estudiante = Persona::find(auth()->user()->id);
            $tarea = Tarea::find($request->tarea_id);
            Notificacion::create([
                'texto' => 'El estudiante '.$estudiante->nombre().' subio un entregable en la tarea '.$tarea->nombre,
                'tipo_notificacion_id' => 1,
                'leido' => 0,
                'email' => $emailProfesorGuia,
                'entregable_id' => $entregable->id
            ]);

            /* Crear mensaje y enviar notificacion */
            $link = $request['urlorigen'].'/entregable/'.$entregable->id.'/info';
            Mail::to($emailProfesorGuia)->send(new NotificacionTarea($entregable,$link));

            //Redirigir a los entregables de la Tarea
            return redirect('/tarea/'.$request['tarea_id'].'/info');
        }
    }

    public function store2(Request $request)
    {
        //dd($request);
        /* obtengo el archivo PDF */
        $file = $request->file('archivo');

        /* Asigno un nombre unico al archivo Pdf */
        $uniqueFileName =  $request['nombre'].'_'.uniqid().'.' . $file->getClientOriginalExtension() ;

        //dd($request);
        if($file->getClientOriginalExtension() != 'pdf')
        {
            //Mensaje
            session()->flash('title', '¡Error!');
            session()->flash('message', 'El documento no es del formato solicitado!');
            session()->flash('icon', 'fa-remove');
            session()->flash('type', 'danger');
            return redirect('entregable/create2');
        }
        else
        {
            /* Creo  la Carpeta en orden de hito a tarea /ID_HITO/ID_TAREA y guardo en servidor */
            $carpeta = $request['hito']."/".$request['tarea'];
            $file->move(storage_path('archivos/'.$carpeta) , $uniqueFileName);
            $rol_persona = auth()->user()->rol_id;
            if($rol_persona == 3)//Profesor Guia
            {
                $subidoPor = 3;
                /*Cambio estado de el primer entregable a revisado*/
                $entregablePadre = Entregable::find($request['entregablePadre']);
                $entregablePadre->estadoEntregable_id = 3;
                $entregablePadre->save();
                $estado = 5;
            }
            else
            {
                $subidoPor = 5;
                $estado = 3;
            }

            /* Creo el entregable para enviarlo a base de datos */
            $carbon = new Carbon();
            $entregable = Entregable::create([
                'nombre' => $request['nombre'],
                'fecha' => $carbon->now(),
                'tarea_id' => $request['tarea'] ,
                'estadoEntregable_id' => $estado,
                'ruta' => $carpeta."/".$uniqueFileName,
                'id_padre' => $request['entregablePadre'],
                'subidoPor' =>$subidoPor,
                'tipo' =>$request['tipo']
            ]);

            //Ver quien hizo el ingreso y notificar al contrario
            $persona = Persona::where('email',"=",auth()->user()->email)->get();
            $nombre_persona = $persona[0]['nombres']." ".$persona[0]['apellidos'];
            

            //dd($rol_persona);
            if($rol_persona == 3)//Profesor Guia
            {
                //Crear Notificacion 
                $proyecto = Hito::where('id',"=",$request['hito'])->get();
                $estudiante_id = Proyecto::where('id',"=",$proyecto[0]['proyecto_id'])->get();
                $eamilEstudiante = User::where('id',"=",$estudiante_id[0]['estudiante_id'])->get();
                $tarea = Tarea::find($request['tarea']);
                $new_entregable = Entregable::find($entregable->id);
                Notificacion::create([
                    'texto' => 'El profesor guía '.$nombre_persona.' subio una revisión del entregable '. $entregable->nombre().'  en la tarea '.$tarea->nombre,
                    'tipo_notificacion_id' => 1,
                    'leido' => 0,
                    'email' => $eamilEstudiante[0]['email'],
                    'entregable_id' => $request['entregablePadre']
                ]); 

                /* Crear mensaje y enviar notificacion */
                //Buscar Email alumno del proyecto
                
                $link = $request['urlorigen'].'/entregable/'.$request['entregablePadre'].'/info';
                //dd($link);
                Mail::to($eamilEstudiante[0]['email'])->send(new NotificacionRevisionEntregable($new_entregable,$link));

                session()->flash('title', '¡Éxito!');
                session()->flash('message', 'El documento se a subido exitosamente y se ha notificado al estudiante');
                session()->flash('icon', 'fa-check');
                session()->flash('type', 'success');
            }
            else // Estudiante
            {
                //Crear Notificacion 
                //Buscar email del Profesor
                $proyecto = Hito::where('id',"=",$request['hito'])->get();
                $profesor_id = Proyecto::where('id',"=",$proyecto[0]['proyecto_id'])->get();
                $emailProfesor = User::where('id',"=",$profesor_id[0]['profesorGuia_id'])->get();

                $tarea = Tarea::find($request['tarea']);
                $new_entregable = Entregable::find($entregable->id);
                Notificacion::create([
                    'texto' => 'El estudiante '.$nombre_persona.' subio una revisión del entregable '. $entregable->nombre().'  en la tarea '.$tarea->nombre,
                    'tipo_notificacion_id' => 1,
                    'leido' => 0,
                    'email' => $emailProfesor,
                    'entregable_id' => $request['entregablePadre']
                ]);

                /* Crear mensaje y enviar notificacion */
                $link = $request['urlorigen'].'/entregable/'.$request['entregablePadre'].'/info';
                //dd($link);
                Mail::to($emailProfesor)->send(new NotificacionRevisionEntregable($new_entregable,$link));

                session()->flash('title', '¡Éxito!');
                session()->flash('message', 'El documento se a subido exitosamente y se ha notificado al profesor guía');
                session()->flash('icon', 'fa-check');
                session()->flash('type', 'success');
            }

            //creo comentario
            $comentario = Comentario::create([
                'texto' => $request['comentario'],
                'entregable_id' => $request['entregablePadre'],
                'user_id' => auth()->user()->id,
                'user_name' => $nombre_persona
            ]);

            return back();
        }
    }

    public function edit(Entregable $entregable)
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
        return view('entregable.edit', compact('tareas','entregable'));
    }
    
    public function update(Request $request, $id)
    {
        $file = $request->file('archivo');

        /* Asigno un nombre unico al archivo Pdf */
        $uniqueFileName =  $request['nombre'].'_'.uniqid().'.' . $file->getClientOriginalExtension() ;

        //dd($request);
        if($file->getClientOriginalExtension() != 'pdf')
        {
            //Mensaje
            session()->flash('title', '¡Error!');
            session()->flash('message', 'El documento no es del formato solicitado!');
            session()->flash('icon', 'fa-remove');
            session()->flash('type', 'danger');
            return redirect('entregable/create2');
        }
        else
        {
            $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:3|max:100',
            ]);

            if ($validator->fails()) 
            {
                session()->flash('title', '¡Error!');
                session()->flash('message', 'Existieron errores!');
                session()->flash('icon', 'fa-check');
                session()->flash('type', 'danger');
                return redirect('entregable/create')->withErrors($validator)->withInput();
            }

            $carpeta = $request['hito']."/".$request['tarea'];
            $file->move(storage_path('archivos/'.$carpeta) , $uniqueFileName);

            $carbon = new Carbon();
           
            $entregable = Entregable::find($id);
            $entregable->nombre = $request['nombre'] ;
            $entregable->fecha = $carbon->now();
            $entregable->tarea_id = $request['tarea_id'] ;
            $entregable->ruta = $carpeta."/".$uniqueFileName;
            $entregable->save();

            session()->flash('title', '¡Éxito!');
            session()->flash('message', 'El entregable se ha editado exitosamente!');
            session()->flash('icon', 'fa-check');
            session()->flash('type', 'success');
            
            return redirect('/tarea/'.$request['tarea_id'].'/info');
        }       
    } 

    public function info(Entregable $entregable,Request $request)
    {
        //dd($entregable);
        $EntregablePadre = $entregable;
        $entregables = array();
        array_push($entregables,$entregable);
        $entregables_proyecto = Entregable::all();
        foreach ($entregables_proyecto as $entre) {
            if($entre->id_padre == $entregable->id)
            {
                array_push($entregables,$entre);
            }
        }
        $comentarios1 = Comentario::all();
        $comentarios = array();
        foreach ($comentarios1 as $comentario) {
            if($comentario->entregable_id == $entregable->id)
            {
                array_push($comentarios, $comentario);
            }
        }
        $comentarios = array_reverse($comentarios);
        //dd($comentarios);
        return view('entregable.info',compact('entregables','EntregablePadre','comentarios'));
    }

    public function delete(Entregable $entregable)
    {
        //dd($entregable);
        $comentarios = Comentario::all();
        $notificaciones = Notificacion::all();
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

        $entregable_nombre = $entregable->nombre;
        $entregable_tarea = $entregable->tarea_id;
        $entregable->delete();

        session()->flash('title', '¡Éxito!');
        session()->flash('message', 'El entregable '. $entregable_nombre.' se ha eliminado exitosamente!');
        session()->flash('icon', 'fa-check');
        session()->flash('type', 'success');

        return redirect('/tarea/'.$entregable_tarea.'/info');
    }

    public function descargar($id)
    {
         $entregable = Entregable::find($id);
         $rutaarchivo= "../storage/archivos/".$entregable->ruta;
         return response()->download($rutaarchivo);
    }
}
