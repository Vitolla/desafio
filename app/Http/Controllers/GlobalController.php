<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Transportadora;
use \App\ServicoAdicional;
use \App\RegrasEconomico;
use \App\RegrasExpresso;
use \App\ValoresEconomico;
use \App\ValoresExpresso;

class GlobalController extends Controller
{
    public function show()
    {
        return view('layouts.home');
    }

    public function calcula()
    {

        $origem = \Request::input('origem');
        $destino = \Request::input('destino');
        $altura = \Request::input('altura');
        $largura = \Request::input('largura');
        $comprimento = \Request::input('comprimento');
        $peso = \Request::input('peso');
        $valor = \Request::input('valor');
        $ar = \Request::input('ar');
        $mp = \Request::input('mp');




        dump(\Request::all());
    }
}
