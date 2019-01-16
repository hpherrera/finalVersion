<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoEntregable extends Model
{
    protected $fillable = ['nombre'];

    public function nombre()
    {
    	return $this->ucfirst($this->nombre);
    }
}
