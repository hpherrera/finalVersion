<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = ['estudiante_id','semestre','year','estado','curso_id'];
}
