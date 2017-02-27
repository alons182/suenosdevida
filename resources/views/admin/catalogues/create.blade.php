@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Catalogue Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.catalogues.store','files'=> true]) !!}
			@include('admin/catalogues/partials/_form')
	{!! Form::close() !!}
</div>
@stop

