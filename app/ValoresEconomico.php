<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ValoresEconomico extends Model
{
    protected $table = 'valores_economico';

    public function transportadora()
    {
        return $this->belongsTo('App\Transportadora');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('pesopadrao', function(Builder $builder) {
            $builder->where('kg_adicional', 0);
        });
    }
}
