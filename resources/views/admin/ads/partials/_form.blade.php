<div class="form-group">
    @if(isset($buttonText))
        @if($currentUser->hasrole('administrator'))
            {!! Form::submit(isset($buttonText) ? $buttonText : 'Crear Anuncio',['class'=>'btn btn-primary'])!!}
        @endif
    @else
        {!! Form::submit('Crear Anuncio',['class'=>'btn btn-primary'])!!}
    @endif

    {!! link_to_route('ads', ($currentUser->hasrole('administrator') ? 'Cancelar' : 'Regresar'), null, ['class'=>'btn
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
        {!! Form::textarea('description',null,['class'=>'form-control','required'=>'required']) !!}
        {!! errors_for('description',$errors) !!}
    </div>

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



</div>


	 