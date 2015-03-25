@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Product Creation</h1>
	
	{!! Form::open(['route'=>'store.admin.products.store','files'=> true]) !!}
		
		@include('admin/products/partials/_form')
		
	{!! Form::close() !!}
</div>
@stop
