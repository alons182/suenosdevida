@extends('layouts.layout')

@section('content')
<section class="main ads">
    <h1>Anuncio: {!! $ad->name !!}</h1>

    <div class="ads-video">
        {!! $ad->video !!}
    </div>
    <div class="ads-company">
        <h1 class="ads-company-name">{!! $ad->company_name !!}</h1>
        <div class="ads-company-image" style="background-image: url('{!! photos_path('ads').$ad->image !!}')">
            <div class="ads-company-logo"><img src="{!! photos_path('ads').'thumb_'.$ad->company_logo !!}" alt="{!! $ad->company_logo !!}" /></div>

        </div>



    </div>
    <div class="ads-company-info">
        <p>{!! $ad->company_info !!}</p>
    </div>
    <div class="ads-description">
        <p>{!! $ad->description !!}</p>
    </div>
    <h1>Galeria</h1>
    <div class="ads-gallery">

        @foreach ($ad->gallery as $photo)
            <div class="ads-gallery-item" style="background-image: url('{!! photos_path('ads') !!}{!! $photo->ad_id !!}/{!! $photo->url_thumb !!}')">
                <!--<img src="{!! photos_path('ads') !!}{!! $photo->ad_id !!}/{!! $photo->url_thumb !!}"
                     data-src="{!! photos_path('ads') !!}{!! $photo->ad_id !!}/{!! $photo->url!!}"
                     alt="{!! $ad->name !!}">-->
            </div>
        @endforeach
    </div>
    <div class="clear"></div>
    @if($hits_per_day != 5 && $hits_per_week != 25)

        @if($ad->hits->count() == 0)
                <div class="ads-comments">
                    {!! Form::open([ 'route'=>['ads.comment', $ad->id] ,'class'=>'form-contact']) !!}


                    <div class="form-group">
                        {!! Form::label('comment','Deja tu comentario:') !!}
                        {!! Form::textarea('comment',null,['class'=>'form-control', 'id' => 'comment', 'disabled'=>'disabled']) !!}
                        {!! errors_for('comment',$errors) !!}
                    </div>
                    <div class="form-group">

                        {!! Form::submit('Enviar',['class'=>'btn btn-primary', 'disabled'=>'disabled'])!!}
                        {!! link_to_route('ads.index','Regresar')!!}
                    </div>

                    {!! Form::close() !!}
                    <span id="countdown" class="countdown" style="background-color:#98ba33;color:#FFFFFF"></span>
                </div>
        @else
            @if($ad->hits->last()->check == 0)
                <div class="ads-comments">
                    {!! Form::open([ 'route'=>['ads.comment', $ad->id] ,'class'=>'form-contact']) !!}


                    <div class="form-group">
                        {!! Form::label('comment','Deja tu comentario:') !!}
                        {!! Form::textarea('comment',null,['class'=>'form-control', 'id' => 'comment', 'disabled'=>'disabled']) !!}
                        {!! errors_for('comment',$errors) !!}
                    </div>
                    <div class="form-group">

                        {!! Form::submit('Enviar',['class'=>'btn btn-primary', 'disabled'=>'disabled'])!!}
                        {!! link_to_route('ads.index','Regresar')!!}
                    </div>

                    {!! Form::close() !!}
                    <span id="countdown" class="countdown" style="background-color:#98ba33;color:#FFFFFF"></span>
                </div>
            @endif
        @endif

    @endif
</section>

@stop
@section('scripts')
    <script>
        $('#countdown').countdown('{!! $targetDate !!}')
                .on('update.countdown', function(event) {
                    $(this).html(event.strftime('%H:%M:%S'));
                })
                .on('finish.countdown',  function(event) {
                    $('#countdown').text('Ya puedes enviar tu comentario');
                    $(".btn").attr('disabled',false);
                    $("#comment").attr('disabled',false);
                });

    </script>



@stop