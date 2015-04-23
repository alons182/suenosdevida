@extends('layouts.layout')

@section('content')
<section class="main ads">
    <h1>Anuncio</h1>
    <h2>{!! $ad->name !!}</h2>
    <div class="ads-video">
        {!! $ad->video !!}
    </div>
    <div class="ads-description">
        <p>{!! $ad->description !!}</p>
    </div>
    <div class="ads-comments">
        {!! Form::open([ 'route'=>['ads.comment', $ad->id] ,'class'=>'form-contact']) !!}


        <div class="form-group">
            {!! Form::label('comment','Deja tu comentario:') !!}
            {!! Form::textarea('comment',null,['class'=>'form-control','disabled' => 'disabled', 'id' => 'comment']) !!}
            {!! errors_for('comment',$errors) !!}
        </div>
        <div class="form-group">

            {!! Form::submit('Enviar',['class'=>'btn btn-primary','disabled' => 'disabled'])!!}
            {!! link_to_route('payments.index','Regresar')!!}
        </div>

        {!! Form::close() !!}
        <span id="countdown" class="countdown" style="background-color:#98ba33;color:#FFFFFF"></span>
    </div>
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