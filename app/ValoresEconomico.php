<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValoresEconomico extends Model
{
    protected $table = 'valores_economico';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
