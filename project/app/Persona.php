<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombres', 'apellidos', 'email'];

    public function user()
    {
        return $this->hasOne('App\User', 'email', 'email');
    }

    public function nombre()
    {
        return ucfirst($this->nombres).' '.ucfirst($this->apellidos);
    }

}
