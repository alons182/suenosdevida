<div class="form-group">
			{!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Categoria',['class'=>'btn btn-primary'])!!}
			{!! link_to_route('store.admin.categories.index', 'Cancelar', null, ['class'=>'btn btn-default'])!!}

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
			{!! Form::label('description','Descripción:')!!}
			{!! Form::textarea('description',null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('description',$errors) !!}
		</div>


		<div class="form-group">
			{!! Form::label('parent_id','Categoria Padre:')!!}
			{!! Form::select('parent_id', ($options) ?  ['root' => 'Root'] + $options : ['root' => 'Root']  , null , ['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('parent_id',$errors) !!}

		</div>

		<div class="form-group">
			{!! Form::label('published','Publicado:')!!}
			{!! Form::select('published', ['1' => 'Yes', '0' => 'No'], null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('published',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('featured','Destacado:')!!}
			{!! Form::select('featured', ['0' => 'No', '1' => 'Yes'], null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('featured',$errors) !!}

		</div>
		
		
</div>
 <div class="col-xs-6 col-md-6">
	 	
	 	<div class="form-group">
			{!! Form::label('image','Imagen:')!!}
 			@if (isset($category))
				 <div class="main_image">
		            @if ($category->image)
		               <img src="{!! photos_path('categories') !!}thumb_{!! $category->image !!}" alt="{!! $category->image !!}"></a>
		            @else
		                <img src="holder.js/140x140" alt="No Image">
		            @endif
		            
		        </div>
	         @endif
			{!! Form::file('image') !!}
			{!! errors_for('image',$errors) !!}
		</div>
 </div>