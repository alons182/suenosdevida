<div class="form-group">
			 @if(isset($buttonText))
			    @if($currentUser->hasrole('administrator'))
			        {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Tienda',['class'=>'btn btn-primary'])!!}
			    @endif
               @else
                    {!! Form::submit('Crear Tienda',['class'=>'btn btn-primary'])!!}
                @endif

			{!! link_to_route('store.admin.shops.index',  ($currentUser->hasrole('administrator') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">


		{!! Form::hidden('responsable_id', isset($shop) ? $shop->responsable_id : null, ['class' => 'form-control']) !!}


		<div class="form-group">
			{!! Form::label('name','Nombre:') !!}
			{!! Form::text('name', null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('name',$errors) !!}

		</div>

		<div class="form-group canton_shops">
			{!! Form::label('canton', 'Canton:') !!}
			{!! Form::select('canton', ['' => ''], null , ['class' => 'form-control']) !!}
			{!! errors_for('canton',$errors) !!}
		</div>
		<div class="form-group">
			{!! Form::label('information','Información:')!!}
			{!! Form::textarea('information',null,['class'=>'form-control','id'=>'ckeditorInfo','required'=>'required']) !!}
			{!! errors_for('information',$errors) !!}
		</div>
		<div class="form-group">
			{!! Form::label('details','Detalles:')!!}
			{!! Form::textarea('details',null,['class'=>'form-control','id'=>'ckeditorDetails','required'=>'required']) !!}
			{!! errors_for('details',$errors) !!}
		</div>


		<div class="form-group">
			{!! Form::label('published','Publicado:')!!}
			{!! Form::select('published', ['1' => 'Si', '0' => 'No'], null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('published',$errors) !!}

		</div>


</div>
		

<div class=" col-sm-6">

	<div class="form-group">
		{!! Form::label('logo','Logo:')!!}
		@if (isset($shop))
			<div class="main_image">
				@if ($shop->logo)
					<img src="{!! photos_path('shops') !!}thumb_{!! $shop->logo !!}" alt="{!! $shop->logo !!}">
				@else
					<img src="holder.js/140x140" alt="No Image">
				@endif

			</div>
		@endif
		{!! Form::file('logo', null) !!}
		{!! errors_for('logo',$errors) !!}
	</div>
	<div class="form-group">
			{!! Form::label('image','Imagen:')!!}
			@if (isset($shop))
				<div class="main_image">
		            @if ($shop->image)
		               <img src="{!! photos_path('shops') !!}thumb_{!! $shop->image !!}" alt="{!! $shop->image !!}">
		            @else
		                <img src="holder.js/140x140" alt="No Image">
		            @endif
		            
		        </div>
		    @endif
			{!! Form::file('image', null) !!}
			{!! errors_for('image',$errors) !!}
		</div>





 </div>
<legend>Responsable</legend>

@include('admin/users/partials/_addPatner',['buttonText' => 'Agregar Responsable'])

<ul class="patners">
	@if(isset($shop) && $shop->responsable_id)

		<li data-id="{!! $shop->responsable_id !!}">
			<span class="delete" data-id="{!! $shop->responsable_id !!}"><i class="glyphicon glyphicon-remove"></i></span>

			<span class="label label-success">{!! $shop->responsable->username !!}</span>


		</li>


	@endif
</ul>