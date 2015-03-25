@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Categoria</h1>
	
	{!! Form::model($category, ['method' => 'put', 'route' => ['store.admin.categories.update', $category->id],'files'=> true ]) !!}
		 @include('admin/categories/partials/_form',['buttonText' => 'Actualizar Categoria'])
	{!! Form::close() !!}
</div>
@stop