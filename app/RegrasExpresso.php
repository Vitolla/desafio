<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegrasExpresso extends Model
{
    protected $table = 'regras_expresso';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
