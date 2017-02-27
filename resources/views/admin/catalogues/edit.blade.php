@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Catálogo</h1>
	
	{!! Form::model($catalogue, ['method' => 'put', 'route' => ['store.admin.catalogues.update', $catalogue->id],'files'=> true ]) !!}
		 @include('admin/catalogues/partials/_form',['buttonText' => 'Actualizar Catálogo'])
	{!! Form::close() !!}
</div>
@stop
