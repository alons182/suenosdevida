
<div class="form-group">

	 @if(isset($buttonText))
    @if($currentUser->hasrole('administrator'))
        {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Usuario',['class'=>'btn btn-primary'])!!}
    @endif
    @else
        {!! Form::submit('Crear Usuario',['class'=>'btn btn-primary'])!!}
    @endif

	{!! link_to_route('users', ($currentUser->hasrole('administrator') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">
		 <!-- Patrocinador Form Input -->
        {!! Form::hidden('parent_id', isset($user) ? $user->parent_id : null, ['class' => 'form-control']) !!}
        {!! Form::hidden('user_id', isset($user) ? $user->id : null, ['class' => 'form-control']) !!}
		<div class="form-group">
			{!! Form::label('username','Username:')!!}
			{!! Form::text('username',null,['class'=>'form-control','required'=>'required'])!!}
			{!! errors_for('username',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('email','Email:')!!}
			{!! Form::email('email',null,['class'=>'form-control','required'=>'required'])!!}
			{!! errors_for('email',$errors) !!}
		</div>
		<div class="form-group">
			{!! Form::label('role','Tipo:')!!}
			{!! Form::select('role',$roles, (isset($user))? $user->roles->first()->id : null,['class'=>'form-control','required'=>'required'])!!}
			{!! errors_for('user_type',$errors) !!}
		</div>
		<div class="form-group">
			{!! Form::label('password','Password:')!!}
			{!! Form::password('password',['class'=>'form-control'])!!}
			{!! errors_for('password',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('password_confirmation','Confirmación de Password:')!!}
			{!! Form::password('password_confirmation',['class'=>'form-control'])!!}

		</div>

		<legend>Patrocinador</legend>

            @include('admin/users/partials/_addPatner')

            <ul class="patners">
                @if(isset($user->parent_id))

                        <li data-id="{!! $user->parent_id !!}">
                            <span class="delete" data-id="{!! $user->parent_id !!}"><i class="glyphicon glyphicon-remove"></i></span>

                            <span class="label label-success">{!! $user->parent->username !!}</span>


                        </li>


                 @endif
            </ul>

        </div>
		
</div>