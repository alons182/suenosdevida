<div class="form-group">
    @if(isset($buttonText))
        @if($currentUser->hasrole('administrator'))
            {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Anuncio',['class'=>'btn btn-primary'])!!}
        @endif
    @else
        {!! Form::submit('Crear Anuncio',['class'=>'btn btn-primary'])!!}
    @endif

    {!! link_to_route('store.admin.ads.index', ($currentUser->hasrole('administrator') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn
    btn-default'])!!}

</div>
<div class="col-xs-12 col-sm-6">
    @if(isset($ad))
        {!! Form::hidden('ad_id',  $ad->id) !!}
    @endif
    <div class="form-group">
        {!! Form::label('name','Nombre:') !!}
        {!! Form::text('name', null,['class'=>'form-control','required'=>'required']) !!}
        {!! errors_for('name',$errors) !!}

    </div>

    <div class="form-group">
        {!! Form::label('description','Descripción:')!!}
        {!! Form::textarea('description',null,['class'=>'form-control','id'=>'ckeditor_description','required'=>'required']) !!}
        {!! errors_for('description',$errors) !!}
    </div>

    <div class="form-group">
        {!! Form::label('all_country','Lugares de visualizacion del anuncio:') !!} <br />
        {!! Form::radio('all_country', '1', true) !!} Para Todo el País <br />
        {!! Form::radio('all_country', '0') !!} Para una zona
        {!! errors_for('all_country',$errors) !!}
    </div>
    <div class="form-group">
        {!! Form::label('province', 'Provincia:') !!}
        {!! Form::select('province', ['' => ''], null,['class'=>'form-control', (isset($ad)) ? ($ad->all_country) ? 'disabled' : '' : '' ]) !!}
        {!! errors_for('province',$errors) !!}
    </div>
    <!-- District Form Input -->
    <div class="form-group">
        {!! Form::label('canton', 'Canton:') !!}
        {!! Form::select('canton', ['Todos' => 'Todos'], null, ['class' => 'form-control',(isset($ad)) ? ($ad->all_country) ? 'disabled' : '' : '' ]) !!}
        {!! errors_for('canton',$errors) !!}
    </div>

    <div class="form-group">
        {!! Form::label('publish_date', 'Fecha Publicado:') !!}
        {!! Form::text('publish_date', null, ['class' => 'form-control datepicker-ads']) !!}
        {!! errors_for('publish_date',$errors) !!}
    </div>
    <div class="form-group">
        {!! Form::label('active_months', 'Meses Activo:') !!}
        {!! Form::text('active_months', null, ['class' => 'form-control']) !!}
        {!! errors_for('active_months',$errors) !!}
    </div>


    <div class="form-group">
        {!! Form::label('published','Publicado:')!!}
        {!! Form::select('published', ['1' => 'Si', '0' => 'No'], null,['class'=>'form-control','required'=>'required'])
        !!}
        {!! errors_for('published',$errors) !!}

    </div>


</div>
<div class=" col-sm-6">

    <div class="form-group">
        {!! Form::label('image','Imagen:')!!}
        @if (isset($ad))
            <div class="main_image">
                @if ($ad->image)
                    <img src="{!! photos_path('ads') !!}{!! $ad->image !!}" alt="{!! $ad->image !!}">
                @else
                    <img src="holder.js/140x140" alt="No Image">
                @endif

            </div>
        @endif
        {!! Form::file('image') !!}
        {!! errors_for('image',$errors) !!}
    </div>

    <div class="form-group">
        {!! Form::label('video','Video:') !!}
        {!! Form::text('video', null,['class'=>'form-control','required'=>'required']) !!}
        {!! errors_for('video',$errors) !!}

    </div>
    <!-- Email Form Input -->
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
        {!! errors_for('email',$errors) !!}
    </div>

    <div class="form-group">
        {!! Form::label('company_logo','Logo Empresa:')!!}
        @if (isset($ad))
            <div class="main_image">
                @if ($ad->company_logo)
                    <img src="{!! photos_path('ads') !!}{!! $ad->company_logo !!}" alt="{!! $ad->company_logo !!}">
                @else
                    <img src="holder.js/140x140" alt="No Image">
                @endif

            </div>
        @endif
        {!! Form::file('company_logo') !!}
        {!! errors_for('company_logo',$errors) !!}
    </div>
    <div class="form-group">
        {!! Form::label('company_name','Nombre Empresa:') !!}
        {!! Form::text('company_name', null,['class'=>'form-control','required'=>'required']) !!}
        {!! errors_for('company_name',$errors) !!}

    </div>
    <div class="form-group">
        {!! Form::label('company_info','Información Empresa:')!!}
        {!! Form::textarea('company_info',null,['class'=>'form-control','id'=>'ckeditor_info','required'=>'required']) !!}
        {!! errors_for('company_info',$errors) !!}
    </div>



</div>
<div class="form-group">

    <legend>Galeria</legend>

    @if(isset($ad))

        <div id="container-gallery">

            <a class="UploadButton btn btn-info" id="UploadButtonAds">Subir Imagen</a>
            <div id="InfoBox"></div>
            <ul id="galleryAds">

                @foreach ($ad->gallery as $photo)
                    <li>
                        <span class="delete" data-imagen="{!! $photo->id !!}"><i class="glyphicon glyphicon-remove"></i></span>
                        <a href="{!! photos_path('ads') !!}{!! $photo->ad_id !!}/{!! $photo->url !!}" data-lightbox="gallery"><img src="{!! photos_path('ads') !!}{!! $photo->ad_id !!}/{!! $photo->url_thumb !!}" alt="img" /></a>
                    </li>
                @endforeach

            </ul>
            <script id="photoTemplate" type="text/x-handlebars-template">

                <li>
                    <span class="delete" data-imagen="@{{ id }}"><i class="glyphicon glyphicon-remove"></i></span>
                    <a href="/images_store/ads/@{{ ad_id }}/@{{ url }}" data-lightbox="gallery"><img src="/images_store/ads/@{{ ad_id }}/@{{ url_thumb }}" alt="img" /></a>
                </li>


            </script>

        </div>
    @else
        <div id="inputs_photos">

            <input class="inputbox btn btn-info" type="button" name="new_photo"  value="Nueva Foto"  id="add_input_photo"/><i class="glyphicon glyphicon-plus-sign"></i>

        </div>

    @endif
</div>


	 