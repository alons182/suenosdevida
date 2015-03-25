@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Pago</h1>
	
	{!! Form::model($payment, ['method' => 'put', 'route' => ['store.admin.payments.update', $payment->id]]) !!}
		
		@include('admin/payments/partials/_form', ['buttonText' => 'Actualizar Pago'])
	 
	{!! Form::close() !!}
</div>
@stop