<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entregable extends Model
{
    protected $fillable = ['nombre', 'fecha','tarea_id','estadoEntregable_id','ruta','id_padre','subidoPor','tipo'];
    
    public function tarea()
    {
    	return $this->belongsTo('App\Tarea', 'tarea_id', 'id');
    }

    public function nombre()
    {
    	return ucfirst($this->nombre);
    }

    public function estado()
    {
    	return $this->belongsTo('App\EstadoEntregable', 'estadoEntregable_id', 'id');
    }
}
