<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Transportadora;
use \App\OrigemDestino;
use \App\Servicos;
use \App\ServicosAdicionais;
use \App\Valores;

class GlobalController extends Controller
{
    public function show()
    {
        return view('layouts.home');
    }

    public function calcula()
    {

        //Pega todos os parametros enviados
        $origem =            \Request::input('origem');
        $origemEstado =      \Request::input('origem_estado');
        $origemCodigoIBGE =  \Request::input('origem_codigo_ibge');
        $destino =           \Request::input('destino');
        $destinoEstado =     \Request::input('destino_estado');
        $destinoCodigoIBGE = \Request::input('destino_codigo_ibge');

        $adicionar =         \Request::input('adicionar');

        //Inicia a merc objeto
        $objeto = array();
        $objeto['altura'] =           \Request::input('altura', 2) != '' ? \Request::input('altura', 2) : 2;
        $objeto['largura'] =          \Request::input('largura', 11) != '' ? \Request::input('largura', 11) : 11;
        $objeto['comprimento'] =      \Request::input('comprimento', 16) != '' ? \Request::input('comprimento', 16) : 16;
        $objeto['peso'] =             \Request::input('peso', 1000) != '' ? \Request::input('peso', 1000) : 1000;
        $objeto['valor_objeto'] =     \Request::input('valor_objeto', 17) != '' ? \Request::input('valor_objeto', 17) : 17;

        //Servicos adicionais
        $aviso_recebimento = \Request::input('aviso_recebimento');
        $mao_propria =       \Request::input('mao_propria');
        $seguro = ($objeto['valor_objeto']>0)?true:false;

        //Cria erro generico para retorno
        $retorno['erro'] = true;
        $retorno['mensagem'] = 'Erro genérico';


        //Valida campos
        if(!$origemEstado && !$origemCodigoIBGE){
            $retorno['mensagem'] = 'CEP de origem incorreto';
            return $retorno;
        }

        if(!$destinoEstado && !$destinoCodigoIBGE){
            $retorno['mensagem'] = 'CEP de destino incorreto';
            return $retorno;
        }

        if($adicionar){
            if($objeto['altura'] < 2 || $objeto['altura'] > 105){
                $retorno['mensagem'] = 'Altura incorreta';
                return $retorno;
            }

            if($objeto['largura'] < 11 || $objeto['largura'] > 105){
                $retorno['mensagem'] = 'Largura incorreta';
                return $retorno;
            }

            if($objeto['comprimento'] < 16 || $objeto['comprimento'] > 105){
                $retorno['mensagem'] = 'Comprimento incorreto';
                return $retorno;
            }

            if($objeto['peso'] < 1000 || $objeto['peso'] > 30000){
                $retorno['mensagem'] = 'Peso incorreto';
                return $retorno;
            }

            if($objeto['valor_objeto'] < 17 || $objeto['valor_objeto'] > 10000){
                $retorno['mensagem'] = 'Seguro incorreto';
                return $retorno;
            }
        }
        else{
            //Se não tiver sido preenchido usar valores minimos padroes
            $objeto['altura'] =           2;
            $objeto['largura'] =          11;
            $objeto['comprimento'] =      16;
            $objeto['peso'] =             1000;
            $objeto['valor_objeto'] =     17;
        }

        //Verifica qual peso será usado para cobrança, peso da encomenda ou cubico
        $objeto['peso_cobranca'] = $objeto['peso'];
        $objeto['peso_cubico'] = $this->calculaPesoCubico($objeto);
        if($objeto['peso_cubico'] > 10000){
            if($objeto['peso_cubico'] > $objeto['peso']){
                $objeto['peso_cobranca'] = $objeto['peso_cubico'];
            }
        }

        //Adiciona objeto pronto no retorno da api
        $retorno['objeto'] = $objeto;

        //Pega transportadoras disponiveis no banco
        $transportadoras = Transportadora::get();

        $retorno['resultado'] = array();
        //Passa por cada uma transportadora no banco de dados
        foreach($transportadoras as $transportadora){
            //Pega os servicos de cada transportadora
            foreach($transportadora->servicos as $servico){

                //Verifica se objeto esta de acordo com as regras do servico da transportadora, se cair em alguma excessao não utiliza servico
                if($objeto['altura'] < $servico->altu_min || $objeto['altura'] > $servico->altu_max){
                    continue;
                }

                if($objeto['largura'] < $servico->larg_min || $objeto['largura'] > $servico->larg_max){
                    continue;
                }

                if($objeto['comprimento'] < $servico->comp_min || $objeto['comprimento'] > $servico->comp_max){
                    continue;
                }

                if($objeto['peso'] < $servico->peso_min || $objeto['peso'] > $servico->peso_max){
                    continue;
                }

                if($objeto['valor_objeto'] < $servico->segu_min || $objeto['valor_objeto'] > $servico->segu_max){
                    continue;
                }

                //Se passou por todas excessoes do servico calcula valores de frete
                $valor_frete = 0;
                $valor_frete = $this->calculaValorTransporte($origemEstado, $origemCodigoIBGE, $destinoEstado, $destinoCodigoIBGE, $servico->tipo, $objeto['peso_cobranca']);

                //Verifica se foi solicitado servico adicional
                $valor_servico = 0;
                if($aviso_recebimento){
                    $valor_servico = $this->adicionaServico(1, $objeto['valor_objeto'], $valor_frete);
                }
                if($mao_propria){
                    $valor_servico = $this->adicionaServico(2, $objeto['valor_objeto'], $valor_frete);
                }
                if($seguro){
                    $valor_servico = $this->adicionaServico(3, $objeto['valor_objeto'], $valor_frete, $objeto['peso_cobranca']);
                }

                $retorno['resultado'][] = array(
                    'id_transportadora' => $transportadora->id,
                    'nome' => $transportadora->nome,
                    'tipo' => ucfirst($servico->tipo),
                    'valor_frete' => $valor_frete,
                    'valor_servico' => $valor_servico,
                    'valor_total' => $valor_frete+$valor_servico,
                    'prazo' => 'Indeterminado' // <---- Não foi passado como se calcula prazo
                );

            }//Fim foreach servicos
        } //Fim foreach transportadoras

        //Se chegou ate aqui não encontrou erro
        $retorno['erro'] = false;
        //verifica se possui resultados
        if(count($retorno['resultado'])){

            $retorno['mensagem'] = 'Ok!';
        }
        else{
            $retorno['mensagem'] = 'Nenhuma transportadora atende as especificações solicitadas!';
        }
        return $retorno;
    }


    //Funcao para calcular o peso cubico
    //Parametros entrada altura, largura e comprimento
    //Retorno Peso Cubico
    public function calculaPesoCubico($objeto){
        $peso_cubico = ($objeto['altura'] * $objeto['largura'] * $objeto['comprimento']) / 6000;

        // Arredonda resultado pra cima
        $peso_cubico = ceil($peso_cubico) * 1000;

        return $peso_cubico;
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
    //Parametros entrada tipo de transporte, estado de origem, codigo ibge de origem, estado de destino, codigo ibge de destino e peso
    //Retorno Valor Total do Transporte
    public function calculaValorTransporte($origemEstado, $origemCodigoIBGE, $destinoEstado, $destinoCodigoIBGE, $tipo='economico', $peso=0){

        $valorTotal = 0;

        //Pega o indicador do trecho
        $indicadorTrecho= OrigemDestino::where('origem', $origemEstado)->where('destino', $destinoEstado)->first();

        //Verifica se origem e destino são mesma cidade
        if( $origemCodigoIBGE == $destinoCodigoIBGE ){
            if($tipo=='economico'){$indicador = 'e'.$indicadorTrecho->indicador;}
            if($tipo=='expresso'){$indicador = 'l'.$indicadorTrecho->indicador;}
        }

        //Verifica se origem e destino são do mesmo estado
        else if($origemEstado == $destinoEstado && $origemCodigoIBGE != $destinoCodigoIBGE){
            $indicador = 'e'.$indicadorTrecho->indicador;
        }

        //Verifica se origem e destino são de estados diferentes, e ambas capitais
        else if($origemEstado != $destinoEstado && $this->isCapital($origemEstado,$origemCodigoIBGE) && $this->isCapital($destinoEstado,$destinoCodigoIBGE)){
            $indicador = 'n'.$indicadorTrecho->indicador;
        }

        //Demais trechos interestaduais
        else{
            $indicador = 'i'.$indicadorTrecho->indicador;
        }

        //Verifica se peso ultrapassa limite 10kg
        $pesoUltrapassado = false;
        if($peso > 10000){
            $pesoUltrapassado = true;
            $pesoRemanescente = $peso-10000;

            //Seta peso para o valor maximo de cobranca por trecho
            $peso = 10000;
        }

        //Calcula o valor do frete baseado no indicador recebido
        $valor = Valores::where('tipo',$tipo)
            ->where('peso_min','<=',$peso)
            ->where('peso_max','>=',$peso)
            ->first();
        $valorTotal = $valor->{$indicador};

        //Verifica se peso esta acima de 10kg, se sim soma com o valor adicional por kg
        if($pesoUltrapassado){
            $valor = Valores::withoutGlobalScopes()->where('tipo',$tipo)->where('kg_adicional',1)->first();

            //Loop para adicionar cobrança em peso remanescente
            while($pesoRemanescente > 0){
                $valorTotal = $valorTotal + $valor->{$indicador};
                $pesoRemanescente = $pesoRemanescente - 1000;
            }
        }

        return $valorTotal;
    }

    //Funcao para adicionar servicos adicionais
    //Parametros entrada valor
    //Retorno Valor Total
    public function adicionaServico($idServico, $valor_objeto, $valor_frete, $peso_objeto=0){
        $valor_servico = 0;
        //Pega servico solicitado
        $servico_adicional = ServicosAdicionais::find($idServico);

        //Aplica valor conforme regra de servico adicional oferecido
        if($servico_adicional->aplicacao == 'frete'){
            $valor_servico = $valor_frete * $servico_adicional->valor;
        }
        if($servico_adicional->aplicacao == 'total'){
            $valor_servico = $servico_adicional->valor;
        }
        if($servico_adicional->aplicacao == 'mercadoria'){
            $valor_servico = $valor_objeto * $servico_adicional->valor;
        }

        //Condicao especial se solicitar seguro e for carga valiosa
        if($idServico == 3){
            //Evitar erro de divisao por zero
            if($peso_objeto>0){
                //Carga valiosa: 1% do valor da mercadoria, para os casos em que o valor da mercadoria divididos pelos quilos do produto resulta em mais de R$3.000 por quilo.
                if(($valor_objeto / ($peso_objeto/1000)) > 3000){
                    $carga_valiosa = ServicosAdicionais::find(4);
                    $valor_servico = $valor_servico + ($valor_objeto * $carga_valiosa->valor);
                }
            }
        }

        return $valor_servico;
    }


}
