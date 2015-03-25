@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Producto</h1>
	
	{!! Form::model($product, ['method' => 'put', 'route' => ['store.admin.products.update', $product->id],'files'=> true ]) !!}
		
		@include('admin/products/partials/_form', ['buttonText' => 'Actualizar Producto'])
	 
	{!! Form::close() !!}
</div>
@stop