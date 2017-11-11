<div id='app'></div>
<script src="{{ asset('js/app.js') }}"></script>

@if(!empty($scripts) && is_array($scripts))
    @foreach ($scripts as $v)
        <script type="text/javascript" src="{{$v['src']}}"></script>
    @endforeach
@endif
