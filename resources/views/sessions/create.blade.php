@extends('layouts.layout')
@section('meta-title','Login')

@section('content')
<div class="main login">

    <h1> Inicio de sesion</h1>

    <div class="col-1">
        {!! Form::open(['route' => 'sessions.store']) !!}
        <!-- Email Form Input -->
        <div class="form-group">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
            {!! errors_for('email',$errors) !!}
        </div>
        <!-- Password Form Input -->
        <div class="form-group">
            {!! Form::label('password', 'Contraseña:') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
            {!! errors_for('password',$errors) !!}
        </div>
        <!-- Log In Form Input -->
        <div class="form-group">
            {!! Form::submit('Identificarse', ['class' => 'btn btn-primary']) !!}
            {!! link_to('password/remind', 'Cambiar contraseña') !!}
        </div>
        {!! Form::close() !!}
    </div>


</div>
@stop