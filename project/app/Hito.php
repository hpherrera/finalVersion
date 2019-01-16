<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hito extends Model
{
    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_termino','proyecto_id','progreso'];

    public function proyecto()
    {
    	return $this->belongsTo('App\Proyecto', 'proyecto_id', 'id');
    }

    public function nombre()
    {
        return ucfirst($this->nombre);
    }

    public function tareas()
    {
        return $this->hasMany('App\Tarea', 'hito_id', 'id');
    }
}
