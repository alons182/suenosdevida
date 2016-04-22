<div class="form-group">
			 @if(isset($buttonText))
			    @if($currentUser->hasrole('administrator') || $currentUser->hasrole('store'))
			        {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Producto',['class'=>'btn btn-primary'])!!}
			    @endif
               @else
                    {!! Form::submit('Crear Producto',['class'=>'btn btn-primary'])!!}
                @endif

			{!! link_to_route('store.admin.products.index',  ($currentUser->hasrole('administrator') || $currentUser->hasrole('store') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">
		@if(isset($product))
			{!! Form::hidden('product_id',  $product->id) !!}
		@endif
		<div class="form-group">
			{!! Form::label('shop_id','Tienda:')!!}
			{!! Form::select('shop_id', ($shops) ?  ['' => ''] + $shops : ['' => '']  , null , ['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('shop_id',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('name','Nombre:') !!}
			{!! Form::text('name', null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('name',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('categories','Categorias:')!!}
			{!! Form::select('categories[]', $categories, isset($selected) ? $selected : null , ['multiple' => 'multiple','class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('categories',$errors) !!}

		</div>

		<div class="form-group">
			{!! Form::label('description','Descripción:')!!}
			{!! Form::textarea('description',null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('description',$errors) !!}
		</div>
		
		<div class="form-group">
			{!! Form::label('price','Precio:')!!}
			<div class="input-group">
				<span class="input-group-addon">&cent;</span>
				{!! Form::text('price',isset($product) ? money($product->price, false) : null,['class'=>'form-control','required'=>'required'])!!}
				{!! errors_for('price',$errors) !!}

			</div>
		</div>
		<div class="form-group">
			{!! Form::label('promo_price','Precio de Promoción:')!!}
			<div class="input-group">
				<span class="input-group-addon">&cent;</span>
				{!! Form::text('promo_price',isset($product) ? money($product->promo_price, false) : null,['class'=>'form-control'])!!}
				{!! errors_for('promo_price',$errors) !!}

			</div>
		</div>
		<div class="form-group">
			{!! Form::label('discount','Descuento:')!!}
			<div class="input-group">
				<span class="input-group-addon">%</span>
				{!! Form::text('discount', null,['class'=>'form-control'])!!}
				{!! errors_for('discount',$errors) !!}

			</div>
		</div>

		<div class="form-group">
			{!! Form::label('published','Publicado:')!!}
			{!! Form::select('published', ['1' => 'Si', '0' => 'No'], null,['class'=>'form-control','required'=>'required']) !!}
			{!! errors_for('published',$errors) !!}

		</div>
		
		
		
</div>
<div class=" col-sm-6">
		<div class="form-group">
			{!! Form::label('sizes','Tallas:')!!} <span class="inputbox btn btn-info btn-sm" id="add_input_size"><i class="glyphicon glyphicon-plus-sign"></i></span>
			<div id="inputs-sizes" class="row ">
				@if(isset($product))
					 @foreach ($product->present()->sizes as $size)
						<div class="col-xs-3 ">
							<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
							{!! Form::text('sizes[]', $size,['class'=>'form-control'])!!}
						</div>
					 @endforeach
				@else
					<div class="col-xs-3 ">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('sizes[]',null,['class'=>'form-control'])!!}
					</div>
					<div class="col-xs-3 ">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('sizes[]',null,['class'=>'form-control'])!!}
					</div>
					<div class="col-xs-3 ">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('sizes[]',null,['class'=>'form-control'])!!}
					</div>
				@endif
				
			</div>
			
			{!! errors_for('sizes',$errors) !!}

		</div>
		<div class="form-group">
			{!! Form::label('colors','Colores:')!!} <span class="inputbox btn btn-info btn-sm" id="add_input_color"><i class="glyphicon glyphicon-plus-sign"></i></span>
			
			<div id="inputs-colors" class="row ">
				@if(isset($product))
					 
					 @foreach ($product->present()->colors as $color)
						<div class="col-xs-3">
							<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
							{!! Form::text('colors[]',$color,['class'=>'form-control colorfield'])!!}
						</div>
					 @endforeach
				@else
					<div class="col-xs-3">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('colors[]',null,['class'=>'form-control colorfield'])!!}
					</div>
					<div class="col-xs-3">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('colors[]',null,['class'=>'form-control colorfield'])!!}
					</div>
					<div class="col-xs-3">
						<span class="delete" ><i class="glyphicon glyphicon-remove"></i></span>
						{!! Form::text('colors[]',null,['class'=>'form-control colorfield'])!!}
					</div>
				@endif
				
			</div>

			
			{!! errors_for('colors',$errors) !!}
	

		</div>

		<div class="form-group">
			{!! Form::label('image','Imagen:')!!}
			@if (isset($product))
				<div class="main_image">
		            @if ($product->image)
		               <img src="{!! photos_path('products') !!}thumb_{!! $product->image !!}" alt="{!! $product->image !!}">
		            @else
		                <img src="holder.js/140x140" alt="No Image">
		            @endif
		            
		        </div>
		    @endif
			{!! Form::file('image') !!}
			{!! errors_for('image',$errors) !!}
		</div>


		<div class="form-group">
		 	
		 	<legend>Galeria</legend>

		 	@if(isset($product))
		 		
		        <div id="container-gallery">
		            
		            <a class="UploadButton btn btn-info" id="UploadButton">Subir Imagen</a>
		            <div id="InfoBox"></div>
		            <ul id="gallery">
		                   
			            @foreach ($product->photos as $photo)
				            <li>
				            	<span class="delete" data-imagen="{!! $photo->id !!}"><i class="glyphicon glyphicon-remove"></i></span>
				            	<a href="{!! photos_path('products') !!}{!! $photo->product_id !!}/{!! $photo->url !!}" data-lightbox="gallery"><img src="{!! photos_path('products') !!}{!! $photo->product_id !!}/{!! $photo->url_thumb !!}" alt="img" /></a>
				            </li>
		            	@endforeach
		                
		            </ul>
		            <script id="photoTemplate" type="text/x-handlebars-template">
	         			   
	         			   <li>
				            	<span class="delete" data-imagen="@{{ id }}"><i class="glyphicon glyphicon-remove"></i></span>
				            	<a href="/images_store/products/@{{ product_id }}/@{{ url }}" data-lightbox="gallery"><img src="/images_store/products/@{{ product_id }}/@{{ url_thumb }}" alt="img" /></a>
				            </li>
				         
				          
				    </script>
		            
		        </div>
		    @else 
		        <div id="inputs_photos">
	                  
		        	<input class="inputbox btn btn-info" type="button" name="new_photo"  value="Nueva Foto"  id="add_input_photo"/><i class="glyphicon glyphicon-plus-sign"></i>
		        
		        </div>
		        
		    @endif
	    </div>


 </div>
	 