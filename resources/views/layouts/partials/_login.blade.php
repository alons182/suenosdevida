
{!! Form::open(['route' => 'sessions.store']) !!}
<!-- Email Form Input -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
    {!! errors_for('email',$errors) !!}
</div>
<!-- Password Form Input -->
<div class="form-group">
    {!! Form::label('password', 'Contraseña:') !!}
    {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
    {!! errors_for('password',$errors) !!}
</div>
<!-- Log In Form Input -->
<div class="form-group">
    {!! Form::submit('Identificarse', ['class' => 'btn btn-primary']) !!}
</div>
<div class="links-index">
    {!! link_to_route('registration.create','Registrate') !!}
    {!! link_to('password/email', 'Cambiar contraseña') !!}
</div>
{!! Form::close() !!}
