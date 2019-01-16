<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfesorGuia extends Model
{
    //

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
