<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValoresExpresso extends Model
{
    protected $table = 'valores_Expresso';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }
}
