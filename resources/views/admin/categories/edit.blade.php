@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Categoria</h1>
	
	{!! Form::model($category, ['method' => 'put', 'route' => ['store.admin.categories.update', $category->id],'files'=> true ]) !!}
		 @include('admin/categories/partials/_form',['buttonText' => 'Actualizar Categoria'])
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
	<script>
		(function($) {

			$('#shop_id').change(function() {
				var $this =  $(this);

				$('#parent_id').empty();
				var option = new Option('Cargando Categorias...', '');
				$('#parent_id').append(option);
				$.get('/store/admin/categories/list',{shop_id: $this.val()}, function(data){
					//console.log(data);
					$('#parent_id').empty();
					$('#parent_id').append('<option value="root">Root</option>');
					$.each(data, function(index,category) {
						var option = new Option(category.name, category.id);
						$('#parent_id').append($(option));
						/*$('select[name="categories[]"]').append('<option value=' + shop.id + '>' + shop.name + '</option>');*/
					});
				});


			});



		})(jQuery);
	</script>
@stop