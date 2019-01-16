<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['texto','entregable_id','user_id','user_name'];

    public function entregable()
    {
    	return $this->belongsTo('App\Entregable', 'entregable_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
