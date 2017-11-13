@php
    $scripts[] = ['src' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.12/jquery.mask.min.js' ];
    $scripts[] = ['src' => asset('js/calculadora.js') ];
@endphp

@extends('base')

@section('content')



    <h2 class="text-center">Calculadora de fretes</h2>

    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 m-t-30">
        {!! Form::open(['id'=>'form', 'class'=>'form-horizontal']) !!}
        <div class="form-group">
            {!! Form::text('origem', '96010280', ['class'=>'form-control cep','placeholder'=>'CEP Origem']) !!}
            {!! Form::hidden ('origem_estado', '', ['class'=>'data-estado']) !!}
            {!! Form::hidden ('origem_codigo_ibge', '', ['class'=>'data-codigo-ibge']) !!}
        </div>
        <div class="form-group">
            {!! Form::text('destino', '50010020', ['class'=>'form-control cep','placeholder'=>'CEP Destino']) !!}
            {!! Form::hidden ('destino_estado', '', ['class'=>'data-estado']) !!}
            {!! Form::hidden ('destino_codigo_ibge', '', ['class'=>'data-codigo-ibge']) !!}
        </div>

        <div class="form-group">
            <label>
                {!! Form::checkbox('adicionar', 1, false, ['class'=>'especificacoes-toggle']) !!}
                Adicionar Especificaçôes
            </label>
        </div>

        <div class="especificacoes-box row" style="display: none">

            <div class="row">
                <div class="col-xs-6 text-center">
                    <div class="form-group">
                        <label>
                            {!! Form::checkbox('aviso_recebimento', 1, false) !!}
                            Aviso de Recebimento (AR)
                        </label>
                    </div>
                </div>
                <div class="col-xs-6 text-center">
                    <div class="form-group">
                        <label>
                            {!! Form::checkbox('mao_propria', 1, false) !!}
                            Mão Própria (MP)
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group">
                    {!! Form::text('altura', '10', ['class'=>'form-control','placeholder'=>'Altura']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('largura', '10', ['class'=>'form-control','placeholder'=>'Largura']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('comprimento', '10', ['class'=>'form-control','placeholder'=>'Comprimento']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('peso', '500', ['class'=>'form-control','placeholder'=>'Peso']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('valor_objeto', '', ['class'=>'form-control','placeholder'=>'Seguro']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit() !!}
        </div>

        {!! Form::close() !!}
    </div>

@stop