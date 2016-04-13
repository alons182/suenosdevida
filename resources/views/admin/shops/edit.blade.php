@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Tienda <a href="#" class="btn btn-default btn-reply">Replicar Productos de..</a></h1>

	@include('admin/shops/partials/_reply')

	{!! Form::model($shop, ['method' => 'put', 'route' => ['store.admin.shops.update', $shop->id],'files'=> true ]) !!}
		
		@include('admin/shops/partials/_form', ['buttonText' => 'Actualizar Tienda'])
	 
	{!! Form::close() !!}

</div>
@stop

@section('scripts')
	<script src="/js/vendor/ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace( 'ckeditorInfo' , {
			uiColor: '#FAFAFA',
			allowedContent : true
		});
		CKEDITOR.replace( 'ckeditorDetails' , {
			uiColor: '#FAFAFA',
			allowedContent : true
		});

		(function($) {

			setTimeout(function(){
				$('#canton option[value="{!! $shop->canton !!}"]').attr("selected", true);
			}, 100);



		})(jQuery);
	</script>
@stop