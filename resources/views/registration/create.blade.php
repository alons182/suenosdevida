@extends('layouts.layout')

@section('content')
    <section class="main register">
        <h1>Registro | <small> Registrandose a la red de {!!  isset($parent_user) ? $parent_user->username : 'Administrador' !!} </small></h1>
        @if(isset($parent_user) && $parent_user->immediateDescendants()->count() >= 10)
            <div class="alert alert-danger">
                No puedes registrate a la red del usuario {!! $parent_user->username !!}, ya ha alcanzado el numero maximo en su red (10 usuarios)
            </div>
        @else
            <div class="col-1">
                {!! Form::open(['route' => 'registration.store']) !!}
                <!-- Patrocinador Form Input -->
                    {!! Form::hidden('parent_id', isset($parent_user) ? $parent_user->id : null, ['class' => 'form-control']) !!}

                <!-- Username Form Input -->
                <div class="form-group">
                    {!! Form::label('username', 'Nombre de usuario:') !!}
                    {!! Form::text('username', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! errors_for('username',$errors) !!}
                </div>
                <!-- Email Form Input -->
                <div class="form-group">
                    {!! Form::label('email', 'Email:') !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! errors_for('email',$errors) !!}
                </div>
                 <!-- Email Confirmation Form Input -->
                    <div class="form-group">
                        {!! Form::label('email_confirmation', 'Confirmación de Email:') !!}
                        {!! Form::email('email_confirmation', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        {!! errors_for('email_confirmation',$errors) !!}
                    </div>
                <!-- Password Form Input -->
                <div class="form-group">
                    {!! Form::label('password', 'Contraseña:') !!}
                    {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! errors_for('password',$errors) !!}
                </div>
                <!-- Password Form Input -->
                <div class="form-group">
                    {!! Form::label('password_confirmation', 'Confirmación contraseña:') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! errors_for('password_confirmation',$errors) !!}
                </div>
                <!-- Terms Form Input -->
                <div class="form-group">
                    {!! Form::label('terms', 'Acepta:') !!}
                    <a href="/terms" target="_blank">Terminos y condiciones</a>
                    {!! Form::checkbox('terms') !!}
                    {!! errors_for('terms',$errors) !!}
                </div>
                <!-- Create Account Form Input -->
                <div class="form-group">
                    {!! Form::submit('Crear Cuenta', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        @endif


    </section>
@stop

