<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegrasEconomico extends Model
{
    protected $table = 'regras_economico';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
