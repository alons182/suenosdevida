@extends('layouts.layout')

@section('content')
<section class="main profile">


        <h1>Edit Profile</h1>
        {!! Form::model($user->profiles, ['method' => 'PATCH', 'route' => ['profile.update', $user->username]]) !!}
    <div class="col-1">
        <!-- First name Form Input -->
        <div class="form-group">
            {!! Form::label('first_name', 'Nombre:') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
            {!! errors_for('first_name',$errors) !!}
        </div>
        <!-- Last name Form Input -->
        <div class="form-group">
            {!! Form::label('last_name', 'Apellidos:') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
            {!! errors_for('last_name',$errors) !!}
        </div>
        <!-- Identification Form Input -->
        <div class="form-group">
            {!! Form::label('ide', 'Identificación:') !!}
            {!! Form::text('ide', null, ['class' => 'form-control']) !!}
            {!! errors_for('ide',$errors) !!}
        </div>
        <!-- Address Form Input -->
        <div class="form-group">
            {!! Form::label('address', 'Dirección:') !!}
            {!! Form::text('address', null, ['class' => 'form-control']) !!}
            {!! errors_for('address',$errors) !!}

        </div>

        <!-- Telephone Form Input -->
        <div class="form-group">
            {!! Form::label('telephone', 'Teléfono:') !!}
            {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
            {!! errors_for('telephone',$errors) !!}
        </div>

    </div>
    <div class="col-2">
        <!-- Estate Form Input -->
        <div class="form-group">
            {!! Form::label('province', 'Provincia:') !!}
            {!! Form::select('province', ['' => ''], null,['class'=>'form-control']) !!}
            {!! errors_for('province',$errors) !!}
        </div>
         <!-- District Form Input -->
          <div class="form-group">
              {!! Form::label('canton', 'Canton:') !!}
              {!! Form::select('canton', ['' => ''], null, ['class' => 'form-control']) !!}
              {!! errors_for('canton',$errors) !!}
          </div>
        <!-- City Form Input -->
        <div class="form-group">
            {!! Form::label('city', 'Ciudad:') !!}
            {!! Form::text('city', null, ['class' => 'form-control']) !!}
            {!! errors_for('city',$errors) !!}
        </div>

        <!-- Bank Form Input -->
        <div class="form-group">
            {!! Form::label('bank', 'Banco:') !!}
            {!! Form::text('bank', null, ['class' => 'form-control']) !!}
            {!! errors_for('bank',$errors) !!}
        </div>

        <!-- Number Account Form Input -->
        <div class="form-group">
            {!! Form::label('number_account', 'Numero de cuenta bancaria:') !!}
            {!! Form::text('number_account', null, ['class' => 'form-control']) !!}
            {!! errors_for('number_account',$errors) !!}
        </div>

        <!-- Skype Form Input -->
        <div class="form-group">
            {!! Form::label('skype', 'Skype:') !!}
            {!! Form::text('skype', null, ['class' => 'form-control']) !!}
            {!! errors_for('skype',$errors) !!}
        </div>
    </div>
       <div class="well">
           <!-- Update Profile Form Input -->
           <div class="form-group">
               {!! Form::submit('Actualizar Perfil', ['class' => 'btn btn-primary']) !!}
           </div>

       </div>
        {!! Form::close() !!}

</section>
@stop
@section('scripts')
    <script>
        (function($) {

                $('#province option[value="{!! $user->profiles->province !!}"]').attr("selected", true);
                $('#province').change();
                $('#canton option[value="{!! $user->profiles->canton !!}"]').attr("selected", true);

        })(jQuery);
    </script>

@stop