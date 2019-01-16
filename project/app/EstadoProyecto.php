<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoProyecto extends Model
{
    protected $fillable = ['estado'];

    public function nombre()
    {
        return ucfirst($this->estado);
    }
}
