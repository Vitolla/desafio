<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Transportadora;
use \App\OrigemDestino;
use \App\ServicoAdicional;
use \App\RegrasEconomico;
use \App\RegrasExpresso;
use \App\Valores;

class GlobalController extends Controller
{
    public function show()
    {
        return view('layouts.home');
    }

    public function calcula()
    {
        $origem =            \Request::input('origem');
        $origemEstado =      \Request::input('origem_estado');
        $origemCodigoIBGE =  \Request::input('origem_codigo_ibge');
        $destino =           \Request::input('destino');
        $destinoEstado =     \Request::input('destino_estado');
        $destinoCodigoIBGE = \Request::input('destino_codigo_ibge');

        $adicionar =         \Request::input('adicionar');

        $altura =       \Request::input('altura');
        $largura =      \Request::input('largura');
        $comprimento =  \Request::input('comprimento');
        $peso =         \Request::input('peso');
        $seguro =       \Request::input('seguro');
        $ar =           \Request::input('ar');
        $mp =           \Request::input('mp');




        //Verifica se destino é capital
        if($this->isCapital($destinoEstado,$destinoCodigoIBGE)){

        }



        $test[]=$origem;
        $test[]=$destino;
        $test[]=$adicionar;
        $test[]=$altura;
        $test[]=$largura;
        $test[]=$comprimento;
        $test[]=$peso;
        $test[]=$seguro;
        $test[]=$ar;
        $test[]=$mp;

        $novo[] = $this->isCapital($origemEstado,$origemCodigoIBGE);
        $novo[] = $this->isCapital($destinoEstado,$destinoCodigoIBGE);

        return $this->calculaValorTransporte($origemEstado, $origemCodigoIBGE, $destinoEstado, $destinoCodigoIBGE, $tipo='economico');
//        return $this->calculaValorTransporte($origemEstado, $origemCodigoIBGE, $destinoEstado, $destinoCodigoIBGE, $tipo='expresso');

        return $novo;
    }


    //Funcao para verificar se regiao é capital
    //Entra estado e codigo do ibge
    //Verifica se é capital se sim retorna true
    public function isCapital($estado,$ibge){
        $retornoCapital = false;

        if($estado == 'AC' && $ibge == '1200401'){$retornoCapital = true;}
        if($estado == 'AL' && $ibge == '2704302'){$retornoCapital = true;}
        if($estado == 'AM' && $ibge == '1302603'){$retornoCapital = true;}
        if($estado == 'AP' && $ibge == '1600303'){$retornoCapital = true;}
        if($estado == 'BA' && $ibge == '2927408'){$retornoCapital = true;}
        if($estado == 'CE' && $ibge == '2304400'){$retornoCapital = true;}
        if($estado == 'DF' && $ibge == '5300108'){$retornoCapital = true;}
        if($estado == 'ES' && $ibge == '3205309'){$retornoCapital = true;}
        if($estado == 'GO' && $ibge == '5208707'){$retornoCapital = true;}
        if($estado == 'MA' && $ibge == '2111300'){$retornoCapital = true;}
        if($estado == 'MG' && $ibge == '3106200'){$retornoCapital = true;}
        if($estado == 'MS' && $ibge == '5002704'){$retornoCapital = true;}
        if($estado == 'MT' && $ibge == '5103403'){$retornoCapital = true;}
        if($estado == 'PA' && $ibge == '1501402'){$retornoCapital = true;}
        if($estado == 'PB' && $ibge == '2507507'){$retornoCapital = true;}
        if($estado == 'PE' && $ibge == '2611606'){$retornoCapital = true;}
        if($estado == 'PI' && $ibge == '2211001'){$retornoCapital = true;}
        if($estado == 'PR' && $ibge == '4106902'){$retornoCapital = true;}
        if($estado == 'RJ' && $ibge == '3304557'){$retornoCapital = true;}
        if($estado == 'RN' && $ibge == '2408102'){$retornoCapital = true;}
        if($estado == 'RO' && $ibge == '1100205'){$retornoCapital = true;}
        if($estado == 'RR' && $ibge == '1400100'){$retornoCapital = true;}
        if($estado == 'RS' && $ibge == '4314902'){$retornoCapital = true;}
        if($estado == 'SC' && $ibge == '4205407'){$retornoCapital = true;}
        if($estado == 'SE' && $ibge == '2800308'){$retornoCapital = true;}
        if($estado == 'SP' && $ibge == '3550308'){$retornoCapital = true;}
        if($estado == 'TO' && $ibge == '1721000'){$retornoCapital = true;}

        return $retornoCapital;
    }

    //Funcao para verificar o preco total do envio
    //Parametros tipo de transporte, estado de origem, codigo ibge de origem, estado de destino, codigo ibge de destino e peso
    //Retorno Valor Total do Transporte
    public function calculaValorTransporte($origemEstado, $origemCodigoIBGE, $destinoEstado, $destinoCodigoIBGE, $tipo='economico', $peso=0){

        $valorTotal = 0;

        //Verifica se origem e destino são mesma cidade
        if( $origemCodigoIBGE == $destinoCodigoIBGE ){
            $indicador = OrigemDestino::where('origem', $origemEstado)->where('destino', $destinoEstado)->first();

            if($tipo=='economico'){$indicador = 'e'.$indicador->indicador;}
            if($tipo=='expresso'){$indicador = 'l'.$indicador->indicador;}

            $valor = Valores::where('tipo',$tipo)
                ->where('peso_min','<=',$peso)
                ->where('peso_max','>=',$peso)
                ->first();
            $valorTotal = $valor->{$indicador};
        }

        //Verifica se origem e destino são do mesmo estado
        else if($origemEstado == $destinoEstado && $origemCodigoIBGE != $destinoCodigoIBGE){

            $indicador = OrigemDestino::where('origem', $origemEstado)->where('destino', $destinoEstado)->first();
            $indicador = 'e'.$indicador->indicador;

            $valor = Valores::where('tipo',$tipo)
                ->where('peso_min','<=',$peso)
                ->where('peso_max','>=',$peso)
                ->first();

            $valorTotal = $valor->{$indicador};
        }

        //Verifica se origem e destino são de estados diferentes, e ambas capitais
        else if($origemEstado != $destinoEstado && $this->isCapital($origemEstado,$origemCodigoIBGE) && $this->isCapital($destinoEstado,$destinoCodigoIBGE)){

            $indicador = OrigemDestino::where('origem', $origemEstado)->where('destino', $destinoEstado)->first();
            $indicador = 'n'.$indicador->indicador;

            $valor = Valores::where('tipo',$tipo)
                ->where('peso_min','<=',$peso)
                ->where('peso_max','>=',$peso)
                ->first();

            $valorTotal = $valor->{$indicador};
        }

        //Demais trechos interestaduais
        else{
            $indicador = OrigemDestino::where('origem', $origemEstado)->where('destino', $destinoEstado)->first();
            $indicador = 'i'.$indicador->indicador;

            $valor = Valores::where('tipo',$tipo)
                ->where('peso_min','<=',$peso)
                ->where('peso_max','>=',$peso)
                ->first();

            $valorTotal = $valor->{$indicador};
        }


        return array($indicador,$valorTotal);
    }


}
