<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notificacion;
use App\Proyecto;
use App\Hito;
use App\Tarea;
use App\Entregable;
use Carbon\Carbon;
use DateTime;

class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	
        $notifications = Notificacion::where('email',"=",auth()->user()->email)->get();

    	return view('notificacion.index', compact('notifications'));
    }

    public function update(Request $request)
    {
    	$notification = Notificacion::find($request->notification_id);
    	$notification->leido= 1;
    	$notification->save();

     	return response()->json(array('info' => $notification->texto,'entregable_id' =>$notification->entregable_id));
    }

    public function view(Request $request)
    {
    	$notification = Notificacion::find($request->notification_id);
    	$notification->leido = 1;
    	$notification->save();

     	return response()->json(array('true' => true));
    }

    public function isAcepted()
    {
        $session_email =  auth()->user()->email ;
        $notis = Notificacion::all();
        $notifications = array();

        foreach ($notis as $notification) {
            if($notification->email == $session_email && $notification->leido == 0)
            {
                array_push($notifications,$notification);   
            }
        }
        //dd($notifications);
        $notifications = array_reverse($notifications);
        $size = sizeof($notifications);
        $returnHTML = view('partials.listnotifications')->with('Notifications',$notifications)->render();
        return response()->json(array('success'=>true, 'html'=>$returnHTML,'size'=>$size));
        //return response()->json(array('Notifications'=>$notifications));
        
    }

    public function entregables()
    {
        if(auth()->user()->rol_id == 5)
        {
            $today = Carbon::now();
            $proyecto = Proyecto::where('estudiante_id',"=",auth()->user()->id)->first();
            $hitos = Hito::where('proyecto_id',"=",$proyecto->id)->get();
            $fuenotificado = 0;
            foreach ($hitos as $hito) {
                $date = new DateTime($hito->fecha_termino);
                $dias = $today->diff($date)->days;

                if($dias <= 5 && $hito->progreso < 100)
                {
                    $notificaciones = Notificacion::where('email',"=",auth()->user()->email )->get();
                    foreach ($notificaciones as $noti) {
                        $datenoti = new DateTime($noti->created_at);
                        $tiene = $today->diff($datenoti)->days;
                        if($tiene == 0 && $noti->entregable_id == -1)
                        {
                            $fuenotificado++;
                        }
                    }

                    if($fuenotificado == 0)
                    {
                        Notificacion::create([
                            'texto' => 'Aviso te quedan pocos días para completar el hito '.$hito->nombre,
                            'tipo_notificacion_id' => 3,
                            'leido' => 0,
                            'email' => auth()->user()->email,
                            'entregable_id' => -1
                        ]);
                    }
                    
                }
            }
        }
            
    }
}
