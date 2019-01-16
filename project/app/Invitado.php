<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    protected $fillable = ['nombre','persona_id','tipo'];

    public function nombre()
    {
        return ucfirst($this->nombre);
    }

    public function persona()
    {
        return $this->hasOne('App\Persona', 'id', 'id');
    }

}
