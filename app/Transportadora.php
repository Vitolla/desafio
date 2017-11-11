<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transportadora extends Model
{
    protected $table = 'transportadora';

    public function servicos()
    {
        return $this->hasMany('App\ServicoAdicional');
    }

    public function regras_economico()
    {
        return $this->hasOne('App\RegrasEconomico');
    }

    public function regras_expresso()
    {
        return $this->hasOne('App\RegrasExpresso');
    }

    public function valores()
    {
        return $this->hasMany('App\Valores');
    }


}
