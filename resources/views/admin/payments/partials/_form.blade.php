<div class="form-group">
			 @if(isset($buttonText))
			    @if($currentUser->hasrole('administrator'))
			        {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Pago',['class'=>'btn btn-primary'])!!}
			    @endif
               @else
                    {!! Form::submit('Crear Pago',['class'=>'btn btn-primary'])!!}
                @endif

			{!! link_to_route('store.admin.payments.index',  ($currentUser->hasrole('administrator') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">
		@if(isset($payment))
			{!! Form::hidden('payment_id',  $payment->id) !!}
		@endif
		 {!! Form::hidden('user_id_payment',null, ['class' => 'form-control']) !!}

        <!-- Tipo de pago Form Input -->
        <div class="form-group">
            {!! Form::label('payment_type', 'Tipo de pago:') !!}
            {!! Form::select('payment_type', ['M' => 'Membrecia (₡20,000)', 'A' => 'Administrativo (₡5,000)'], null,['class'=>'form-control']) !!}
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


        <legend>Al Usuario:</legend>

                    @include('admin/users/partials/_addPatner',['buttonText'=>'Agregar Usuario'])

                    <ul class="patners">
                        @if(isset($user->parent_id))

                                <li data-id="{!! $user->parent_id !!}">
                                    <span class="delete" data-id="{!! $user->parent_id !!}"><i class="glyphicon glyphicon-remove"></i></span>

                                    <span class="label label-success">{!! $user->parent->username !!}</span>


                                </li>


                         @endif
                    </ul>



 </div>
	 