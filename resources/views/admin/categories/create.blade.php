@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Category Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.categories.store','files'=> true]) !!}
			@include('admin/categories/partials/_form')
	{!! Form::close() !!}
</div>
@stop
