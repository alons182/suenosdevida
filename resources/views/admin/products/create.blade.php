@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Product Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.products.store','files'=> true]) !!}
		
		@include('admin/products/partials/_form')
		
	{!! Form::close() !!}
</div>
@stop
@section('scripts')
	<script>
		(function($) {

			$('#shop_id').change(function() {
				var $this =  $(this);

				$('select[name="categories[]"]').empty();
				$.get('/store/admin/categories/list',{shop_id: $this.val()}, function(data){
					console.log(data);
					$.each(data, function(text,key) {
						var option = new Option(key, text);
						$('select[name="categories[]"]').append($(option));
						/*$('select[name="categories[]"]').append('<option value=' + shop.id + '>' + shop.name + '</option>');*/
					});
				});


			});



		})(jQuery);
	</script>
@stop