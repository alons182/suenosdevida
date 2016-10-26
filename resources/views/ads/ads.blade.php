@extends('layouts.layout')

@section('content')
    <div class="payments-ads">

        <h1>Publicidad | <small>Del: <b>{{ $startOfWeek  }}</b> al <b>{{ $endOfWeek  }}</b> (Puedes ver 5 anuncios por dia de cada tipo) Dia actual: <b>{{ $today  }}</b> </small></h1>

        <div class="payments-ads-not-seen">


            @forelse ($ads as $ad)

                @if(auth()->check())
                    @if($hits_per_day != 5 && $hits_per_week != 25)
                        @if($ad->hits->count() == 0)

                            <div class="payments-ad">

                                <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                    <span class="ad_id">{!! $ad->id !!}</span>
                                    @if($ad->image)
                                        <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                    @else
                                        <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                    @endif
                                </a>

                            </div>
                        @else
                            @if(!$ad->hits()->where('user_id',auth()->user()->id)->get()->last())
                                <div class="payments-ad">
                                    <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                        <span class="ad_id">{!! $ad->id !!}</span>
                                        @if($ad->image)
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                        @else
                                            <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        @endif
                                    </a>
                                </div>
                            @else
                                @if($ad->hits()->where('user_id',auth()->user()->id)->get()->last()->check == 0)
                                    <div class="payments-ad">
                                        <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            @if($ad->image)
                                                <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                            @else
                                                <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                            @endif
                                        </a>
                                    </div>

                                @else
                                    <div class="payments-ad">
                                        <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!!  $ad->hits()->where('user_id',auth()->user()->id)->get()->last()->hit_date  !!}">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            @if($ad->image)
                                                <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                            @else
                                                <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                            @endif
                                        </a>
                                    </div>

                                @endif
                            @endif

                        @endif
                    @else
                        <div class="payments-ad">
                            <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($hits_per_week == 25) ? 'Has completado tus 5 dias por semana' : 'Solo 5 por dia' !!}">
                                @if($ad->image)
                                    <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185" />                @else
                                    <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                @endif
                            </a>
                        </div>

                    @endif
                @else
                    <div class="payments-ad">

                        <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                            <span class="ad_id">{!! $ad->id !!}</span>
                            @if($ad->image)
                                <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                            @else
                                <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                            @endif
                        </a>

                    </div>
                @endif



            @empty
                <p>No hay publicidad para ver</p>
            @endforelse
        </div>
    </div>
@stop
