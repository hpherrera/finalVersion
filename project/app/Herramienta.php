<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    protected $fillable = ['herramienta'];

   	public function nombre()
    {
        return ucfirst($this->herramienta);
    }
}
