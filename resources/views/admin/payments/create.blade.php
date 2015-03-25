@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Agregando pago</h1>

	{!! Form::open(['route'=>'store.admin.payments.store']) !!}

		@include('admin/payments/partials/_form')

	{!! Form::close() !!}
</div>

</section>
@stop