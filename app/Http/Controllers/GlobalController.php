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
        return view('welcome');
    }
}
