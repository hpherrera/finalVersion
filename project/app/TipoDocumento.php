<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $fillable = ['nombre'];

    public function nombre()
    {
        return ucfirst($this->nombre);
    }
}
