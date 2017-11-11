<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ValoresExpresso extends Model
{
    protected $table = 'valores_Expresso';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('peso_padrao', function(Builder $builder) {
            $builder->where('kg_adicional', 0);
        });
    }
}
