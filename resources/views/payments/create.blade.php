@extends('layouts.layout')

@section('content')
<section class="main register">
    <h1>Agregando pago </h1>
    <div class="col-1">
        {!! Form::open(['route' => 'payments.store']) !!}

        <!-- Tipo de pago Form Input -->
        <div class="form-group">
            {!! Form::label('payment_type', 'Tipo de pago:') !!}
            {!! Form::select('payment_type', ['M' => 'Membresia Nivel 1 (₡3,000)'], null,['class'=>'form-control']) !!}
            {!! errors_for('payment_type',$errors) !!}
        </div>
        <!-- Banco Form Input -->
        <div class="form-group">
            {!! Form::label('bank', 'Banco:') !!}
            {!! Form::text('bank', null, ['class' => 'form-control']) !!}
            {!! errors_for('bank',$errors) !!}
        </div>
        <!-- Numero de deposito Form Input -->
        <div class="form-group">
            {!! Form::label('transfer_number', 'Numero de deposito o transferencia:') !!}
            {!! Form::text('transfer_number', null, ['class' => 'form-control']) !!}
            {!! errors_for('transfer_number',$errors) !!}
        </div>
        <!-- Transfer_date Form Input -->
        <div class="form-group">
            {!! Form::label('transfer_date', 'Fecha realizado:') !!}
            {!! Form::text('transfer_date', null, ['class' => 'form-control datepicker']) !!}
            {!! errors_for('transfer_date',$errors) !!}
        </div>
        <!-- Create Account Form Input -->
        <div class="form-group">
            {!! Form::submit('Agregar', ['class' => 'btn btn-primary']) !!}
            {!! link_to_route('payments.index','Regresar')!!}
        </div>

        {!! Form::close() !!}
    </div>

</section>
@stop