@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Usuario</h1>
	{!! Form::model($user, ['method' => 'put', 'route' => ['store.admin.users.update', $user->id] ]) !!}
		 @include('admin/users/partials/_form',['buttonText' => 'Actualizar Usuario'])
	{!! Form::close() !!}
</div>
@stop