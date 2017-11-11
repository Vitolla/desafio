<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('includes.head')
    </head>
    <body>
        <div class="container-fluid">

            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center">Desafio Melhor Envio</h1>
                </div>
            </div>

            <div id="main" class="row">
                @yield('content')
            </div>

        </div>
    </body>
    @include('includes.footer')
</html>
