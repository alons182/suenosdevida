<div class="form-group">
			{!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Catálogo',['class'=>'btn btn-primary'])!!}
			{!! link_to_route('store.admin.catalogues.index', 'Cancelar', null, ['class'=>'btn btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">

		<div class="form-group">
			{!! Form::label('shop_id','Tienda:')!!}
			{!! Form::select('shop_id', ($shops) ?  ['' => ''] + $shops : ['' => '']  , null , ['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('shop_id',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('name','Nombre:')!!}
			{!! Form::text('name',null,['class'=>'form-control','required'=>'required'])!!}
			{!! errors_for('name',$errors) !!}
			

		</div>
		<div class="form-group">
			{!! Form::label('url','Url:')!!}
			{!! Form::text('url',null,['class'=>'form-control','required'=>'required'])!!}
			{!! errors_for('url',$errors) !!}
			

		</div>

		
		
</div>
 <div class="col-xs-6 col-md-6">
	 	
	 	<div class="form-group">
			{!! Form::label('image','Imagen:')!!}
 			@if (isset($catalogue))
				 <div class="main_image">
		            @if ($catalogue->image)
		               <img src="{!! photos_path('catalogues') !!}thumb_{!! $catalogue->image !!}" alt="{!! $catalogue->image !!}"></a>
		            @else
		                <img src="holder.js/140x140" alt="No Image">
		            @endif
		            
		        </div>
	         @endif
			{!! Form::file('image') !!}
			{!! errors_for('image',$errors) !!}
		</div>
 </div>