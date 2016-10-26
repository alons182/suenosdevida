@extends('layouts.layout-iframe')

@section('content')
<section class="main ads">


    <iframe src="{{ $ad->url }}" frameborder="0"  width="100%" height="960"> </iframe>
    {{ csrf_field() }}
    @if(auth()->check())
        @if($hits_per_day != 5 && $hits_per_week != 25)

            @if($ad->hits->count() == 0)
                <span id="countdown" class="countdown countdown-web" style="background-color:#98ba33;color:#FFFFFF"></span>
            @else
                @if(!$ad->hits()->where('user_id',auth()->user()->id)->get()->last())
                    <span id="countdown" class="countdown countdown-web" style="background-color:#98ba33;color:#FFFFFF"></span>
                @else
                    @if($ad->hits()->where('user_id',auth()->user()->id)->get()->last()->check == 0)
                        <span id="countdown" class="countdown countdown-web" style="background-color:#98ba33;color:#FFFFFF"></span>
                    @endif
                @endif
            @endif

        @endif
    @endif

</section>

@stop
@section('scripts')
    @if(auth()->check())
        @if($hits_per_day != 5 && $hits_per_week != 25)

            @if($ad->hits->count() == 0)
                <script>
                    $('#countdown').countdown('{!! $targetDate !!}')
                            .on('update.countdown', function(event) {
                                $(this).html(event.strftime('%H:%M:%S'));
                            })
                            .on('finish.countdown',  function(event) {

                                $.ajax({
                                    type: 'POST',
                                    url: '/ads/viewed/{{ $ad->id }}',
                                    data:{ _token: $('input[name=_token]').val()},
                                    success: function (resp) {
                                        console.log(resp);
                                        $('#countdown').text(resp);
                                    },
                                    error: function () {
                                        console.log('error');

                                    }
                                });


                            });

                </script>
            @else
                @if(!$ad->hits()->where('user_id',auth()->user()->id)->get()->last())
                    <script>
                        $('#countdown').countdown('{!! $targetDate !!}')
                                .on('update.countdown', function(event) {
                                    $(this).html(event.strftime('%H:%M:%S'));
                                })
                                .on('finish.countdown',  function(event) {

                                    $.ajax({
                                        type: 'POST',
                                        url: '/ads/viewed/{{ $ad->id }}',
                                        data:{ _token: $('input[name=_token]').val()},
                                        success: function (resp) {
                                            console.log(resp);
                                            $('#countdown').text(resp);
                                        },
                                        error: function () {
                                            console.log('error');

                                        }
                                    });


                                });

                    </script>
                @else
                    @if($ad->hits()->where('user_id',auth()->user()->id)->get()->last()->check == 0)
                        <script>
                            $('#countdown').countdown('{!! $targetDate !!}')
                                    .on('update.countdown', function(event) {
                                        $(this).html(event.strftime('%H:%M:%S'));
                                    })
                                    .on('finish.countdown',  function(event) {

                                        $.ajax({
                                            type: 'POST',
                                            url: '/ads/viewed/{{ $ad->id }}',
                                            data:{ _token: $('input[name=_token]').val()},
                                            success: function (resp) {
                                                console.log(resp);
                                                $('#countdown').text(resp);
                                            },
                                            error: function () {
                                                console.log('error');

                                            }
                                        });


                                    });

                        </script>
                    @endif
                @endif
            @endif

        @endif
    @endif




@stop