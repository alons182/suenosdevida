@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Register</h1>
	{!! Form::open(['route'=>'user_register.store','']) !!}
		@include('admin/users/partials/_form')
	{!! Form::close() !!}
</div>
@stop