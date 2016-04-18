@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Producto</h1>
	
	{!! Form::model($product, ['method' => 'put', 'route' => ['store.admin.products.update', $product->id],'files'=> true ]) !!}
		
		@include('admin/products/partials/_form', ['buttonText' => 'Actualizar Producto'])
	 
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
	<script>
		(function($) {

			$('#shop_id').change(function() {
				var $this =  $(this);

				$('select[name="categories[]"]').empty();
				var option = new Option('Cargando Categorias...', '');
				$('select[name="categories[]"]').append(option);
				$.get('/store/admin/categories/list',{shop_id: $this.val()}, function(data){
					//console.log(data);
					$('select[name="categories[]"]').empty();
					$.each(data, function(index,category) {
						var option = new Option(category.name, category.id);
						$('select[name="categories[]"]').append($(option));
						/*$('select[name="categories[]"]').append('<option value=' + shop.id + '>' + shop.name + '</option>');*/
					});
				});


			});



		})(jQuery);
	</script>
@stop