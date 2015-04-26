@extends('admin.layouts.layout')

@section('content')

    <div class="starter-template">
        <div class="col-xs-12 col-md-8">
            <h1>Creación de usuarios</h1>
            {!! Form::open(['route'=>'store_users']) !!}

            <div class="form-group">
                {!! Form::label('cant_users', 'Cantidad de usuarios a crear:') !!}
                {!! Form::text('cant_users', 5, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('user_id', 'Id del usuario padre:') !!} </label>
                {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::submit('Crear Usuarios',['class'=>'btn btn-primary'])!!}


            {!! Form::close() !!}


            <h1>Creación de pagos</h1>
            {!! Form::open(['route'=>'store_payments']) !!}

            <div class="form-group">
                {!! Form::label('cant_users', 'Del usuario:') !!}
                {!! Form::text('to', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('user_id', 'Al usuario:') !!} </label>
                {!! Form::text('from', null, ['class' => 'form-control']) !!}
            </div>
            {!! Form::submit('Crear Pagos',['class'=>'btn btn-primary'])!!}


            {!! Form::close() !!}
        </div>
    </div>

@stop