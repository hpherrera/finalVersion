<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $fillable = ['matricula','persona_id','ocupado'];

    public function matricula()
    {
        return ucfirst($this->matricula);
    }

    public function persona()
    {
        return $this->hasOne('App\Persona', 'persona_id', 'id');
    }

}

