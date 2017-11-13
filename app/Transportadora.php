<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transportadora extends Model
{
    protected $table = 'transportadoras';

    public function servicos()
    {
        return $this->hasMany('App\Servicos');
    }

    public function servicos_adicionais()
    {
        return $this->hasMany('App\ServicosAdicionais');
    }

    public function valores()
    {
        return $this->hasMany('App\Valores');
    }


}
