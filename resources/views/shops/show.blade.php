@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | {!! $shop->name !!}
@stop
@section('content')


<div class="shops-details">



    <div class="shop-info">

        <h1 class="shop-name item_name">{!! $shop->name !!} </h1>



        <div class="shop-image " style="background-image: url('{!! photos_path('shops').$shop->image !!}')">
            <div class="shop-logo">
                <img src="{!! photos_path('shops').'thumb_'.$shop->logo !!}" alt="{!! $shop->logo !!}" />
            </div>

        </div>
        <div class="shop-information">
            {!! $shop->information !!}
        </div>
        <div class="shop-details">
            {!! $shop->details !!}
        </div>
        <div class="shop-social">
            <span class="shop-share-title">Compartir:</span>
            <a class="icon-facebook" title="Facebook" href="#"
               onclick="
                                window.open(
                                  'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),
                                  'facebook-share-dialog',
                                  'width=626,height=436');
                                return false;">

            </a>
            <a class="icon-twitter" href="https://twitter.com/share?url={!! Request::url()!!}"
               target="_blank"></a>
            <a class="icon-googleplus" href="https://plus.google.com/share?url={!! Request::url()!!}" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"></a>
        </div>
        <div class="shop-catalogues">
           @foreach($shop->catalogues as $catalogue)
             <iframe src="{{ $catalogue->url }}" frameborder="0"  width="100%" height="450"> </iframe> 
            @endforeach
        </div>
         @if(auth()->check())
            <div class="form-group">
                <a href="#" class="btn btn-primary btn-catalogue-query">Solicitar del catálogo</a>
                
            </div>
            <div class="shop-catalogues-query">
                
                {!! Form::open([ 'route'=>['catalogues.resquest', $shop->id] ,'class'=>'form-contact']) !!}


                <div class="form-group">
                    {!! Form::label('first_name', 'Nombre:') !!}
                    {!! Form::text('first_name', $currentUser->profiles->first_name, ['class' => 'form-control']) !!}
                    {!! errors_for('first_name',$errors) !!}
                </div>
                <!-- Last name Form Input -->
                <div class="form-group">
                    {!! Form::label('last_name', 'Apellidos:') !!}
                    {!! Form::text('last_name', $currentUser->profiles->last_name, ['class' => 'form-control']) !!}
                    {!! errors_for('last_name',$errors) !!}
                </div>
                <!-- Address Form Input -->
                <div class="form-group">
                    {!! Form::label('address', 'Dirección:') !!}
                    {!! Form::text('address', $currentUser->profiles->address, ['class' => 'form-control']) !!}
                    {!! errors_for('address',$errors) !!}

                </div>

                <!-- Telephone Form Input -->
                <div class="form-group">
                    {!! Form::label('telephone', 'Teléfono:') !!}
                    {!! Form::text('telephone', $currentUser->profiles->telephone, ['class' => 'form-control']) !!}
                    {!! errors_for('telephone',$errors) !!}
                </div>
                <!-- Email Form Input -->
                <div class="form-group">
                    {!! Form::label('email', 'Email:') !!}
                    {!! Form::email('email', $currentUser->email, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! errors_for('email',$errors) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('comment','Solicitud:') !!}
                    {!! Form::textarea('comment',null,['class'=>'form-control', 'id' => 'comment', 'placeholder' =>'Solicita lo que deseas del catálogo', 'required' => 'required']) !!}
                    {!! errors_for('comment',$errors) !!}
                </div>
                <div class="form-group">

                    {!! Form::submit('Enviar Solicitud',['class'=>'btn btn-primary'])!!}
                    
                </div>

                {!! Form::close() !!}
               
            </div>
        @endif


    </div>
    <div class="clear"></div>
    <div class="shop-categories">
        <h1>Categorias</h1>
        @include('layouts.partials._list_categories', ['categories' => $categories])
    </div>

</div>
@stop

