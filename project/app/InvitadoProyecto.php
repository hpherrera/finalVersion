<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitadoProyecto extends Model
{
    protected $fillable = ['persona_id','proyecto_id'];
}
