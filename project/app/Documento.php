<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = ['ruta', 'Entregable_id'];

    public function entregable()
    {
    	return $this->belongsTo('App\Entregable', 'Entregable_id', 'id');
   	}
}
