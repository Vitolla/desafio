<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicoAdicional extends Model
{
    protected $table = 'servico_adicional';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
