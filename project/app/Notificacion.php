<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $fillable = ['tipo_notificacion_id', 'texto', 'leido','email','entregable_id'];

    public function texto()
    {
        return ucfirst($this->texto);
    }

    public function destinatario()
    {
    	return $this->belongsTo('App\User', 'email', 'email');
    }

    public function tipo()
    {
    	return $this->belongsTo('App\TipoNotificacion','tipo_notificacion_id','id');
    }
}
