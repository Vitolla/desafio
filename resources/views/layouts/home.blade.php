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
            {!! Form::text('origem', '9601028', ['id'=>'origem','class'=>'form-control cep','placeholder'=>'CEP Origem']) !!}
        </div>
        <div class="form-group">
            {!! Form::text('destino', '96010280', ['class'=>'form-control cep','placeholder'=>'CEP Destino']) !!}
        </div>

        <div class="form-group">
            <label>
                {!! Form::checkbox('adicionar', 1, false, ['class'=>'especificacoes-toggle']) !!}
                Adicionar Especificaçôes
            </label>
        </div>

        <div class="especificacoes-box row" style="display: none">
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
                    {!! Form::text('seguro', '', ['class'=>'form-control','placeholder'=>'Seguro']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit() !!}
        </div>

        {!! Form::close() !!}
    </div>

@stop