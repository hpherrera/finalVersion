<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['nombre'];

    public function nombre()
    {
        return ucfirst($this->nombre);
    }
}
