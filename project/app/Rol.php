<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Rol extends Model
{
    //
    protected $fillable = ['nombre'];

    public function users()
    {
    	return $this->belongsToMany('App\User', 'user_rols');
    }

    public function nombre()
    {
        return ucfirst($this->nombre);
    }
}
