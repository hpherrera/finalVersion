<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = ['nombre', 'fecha_limite', 'comentario','hito_id'];

    public function hito()
    {
    	return $this->belongsTo('App\Hito', 'hito_id', 'id');
    }

    public function nombre()
    {
    	return ucfirst($this->nombre);
    }
}
