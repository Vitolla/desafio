<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<title>Desafio MelhorEnvio</title>

@if(!empty($styles) && is_array($styles))
    @foreach ($styles as $v)
        <link rel="stylesheet" href="{{$v['src']}}" >
    @endforeach
@endif