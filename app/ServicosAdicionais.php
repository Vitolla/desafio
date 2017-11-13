<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicosAdicionais extends Model
{
    protected $table = 'servicos_adicionais';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
