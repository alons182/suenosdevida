@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Anuncio</h1>
	
	{!! Form::model($ad, ['method' => 'put', 'route' => ['store.admin.ads.update', $ad->id],'files'=> true ]) !!}

		@include('admin/ads/partials/_form', ['buttonText' => 'Actualizar Anuncio'])
	 
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
    <script>
        (function($) {

            setTimeout(function(){
                $('#province option[value="{!! $ad->province !!}"]').attr("selected", true);
                $('#province').change();
                $('#canton option[value="{!! $ad->canton !!}"]').attr("selected", true);
            }, 100);



        })(jQuery);
    </script>
@stop