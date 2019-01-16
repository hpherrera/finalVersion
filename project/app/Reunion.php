<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $fillable = ['fecha','profesor_guia_id','estudiante_id','hora'];

    public function persona()
    {
    	return $this->belongsTo('App\Persona', 'estudiante_id', 'id');
    }
}
