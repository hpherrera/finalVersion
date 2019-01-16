<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfesorCurso extends Model
{
	protected $fillable = ['curso_id','profesor_id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function curso()
    {
    	return $this->belongsTo('App\Curso', 'curso_id', 'id');
    }
    
}
