<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicos extends Model
{
    protected $table = 'servicos';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
