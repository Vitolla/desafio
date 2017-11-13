@php

    $styles[] = ['src' => 'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css' ];

    $scripts[] = ['src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.12/jquery.mask.min.js' ];
    $scripts[] = ['src' => 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js' ];
    $scripts[] = ['src' => asset('js/calculadora.js') ];
@endphp

@extends('base')

@section('content')

    <div id="calculadora" class="row col-xs-12">

        <h2 class="text-center">Calculadora de fretes</h2>

        <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
            {!! Form::open(['id'=>'form', 'class'=>'form-horizontal']) !!}
            <div class="form-group">
                {!! Form::tel('origem', '96010280', ['class'=>'form-control cep','placeholder'=>'CEP Origem']) !!}
                {!! Form::hidden ('origem_estado', '', ['class'=>'data-estado']) !!}
                {!! Form::hidden ('origem_codigo_ibge', '', ['id'=>'origem','class'=>'data-codigo-ibge']) !!}
            </div>
            <div class="form-group">
                {!! Form::tel('destino', '50010020', ['class'=>'form-control cep','placeholder'=>'CEP Destino']) !!}
                {!! Form::hidden ('destino_estado', '', ['class'=>'data-estado']) !!}
                {!! Form::hidden ('destino_codigo_ibge', '', ['id'=>'destino','class'=>'data-codigo-ibge']) !!}
            </div>

            <div class="form-group">
                <label class="pointer">
                    {!! Form::checkbox('adicionar', 1, false, ['class'=>'especificacoes-toggle']) !!}
                    Adicionar Especificaçôes
                </label>
            </div>

            <div class="especificacoes-box row" style="display: none">

                <div class="row">
                    <div class="col-xs-6 text-center">
                        <div class="form-group">
                            <label class="pointer">
                                {!! Form::checkbox('aviso_recebimento', 1, false) !!}
                                Aviso de Recebimento (AR)
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-6 text-center">
                        <div class="form-group">
                            <label class="pointer">
                                {!! Form::checkbox('mao_propria', 1, false) !!}
                                Mão Própria (MP)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="form-group">
                        {!! Form::tel('altura', '', ['class'=>'form-control numero','placeholder'=>'Altura']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::tel('largura', '', ['class'=>'form-control numero','placeholder'=>'Largura']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::tel('comprimento', '', ['class'=>'form-control numero','placeholder'=>'Comprimento']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::tel('peso', '', ['class'=>'form-control numero','placeholder'=>'Peso']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::tel('valor_objeto', '', ['class'=>'form-control numero','placeholder'=>'Seguro']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::button('Calcular', ['id'=>'btn-calcula', 'disabled'=>'disabled']) !!}
            </div>

            {!! Form::close() !!}
        </div>

    </div>

    <div id="resultados" class="container-fluid" style="display: none">

        <h2 class="text-center">Fretes Disponíveis</h2>

        <div class="responsive-table" style="width: 100%; position: relative; float: left; overflow-x: auto">
            <table id="example" class="display" cellspacing="0" width="100%" style="margin-bottom: 20px;">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Prazo</th>
                </tr>
                </thead>
                <tbody id="resultado_body">

                </tbody>
            </table>
        </div>


        <div class="form-group text-center">
            {!! Form::button('Calcular de novo', ['id'=>'btn-calcula-denovo']) !!}
        </div>

    </div>

@stop