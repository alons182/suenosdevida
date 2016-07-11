@extends('layouts.layout')

@section('content')
    <div class="payments-ads">
        <h1>Publicidad | <small>Del: <b>{{ $startOfWeek  }}</b> al <b>{{ $endOfWeek  }}</b> (Puedes ver 5 anuncios por dia) Dia actual: <b>{{ $today  }}</b> </small></h1>

        <div class="payments-ads-not-seen">


            @forelse ($ads as $ad)

                <div class="payments-ad">
                    @if($hits_per_day != 5 && $hits_per_week != 25)

                        @if($ad->hits->count() == 0)
                            <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                <span class="ad_id">{!! $ad->id !!}</span>
                                @if($ad->image)
                                    <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                @else
                                    <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                @endif
                            </a>
                        @else
                            @if($ad->hits->last()->check == 0)
                                <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                    <span class="ad_id">{!! $ad->id !!}</span>
                                    @if($ad->image)
                                        <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185"/>
                                    @else
                                        <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                    @endif
                                </a>

                            @else
                                @if($ad->image)
                                    <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185" />
                                        </a>
                                @else
                                    <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        </a>
                                @endif

                            @endif
                        @endif


                    @else
                        @if($ad->image)
                            <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($hits_per_week == 25) ? 'Has completado tus 5 dias por semana' : 'Solo 5 por dia' !!}">
                                    <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="185"  height="185" />
                                </a>
                        @else
                            <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($hits_per_week == 25) ? 'Has completado tus 5 dias por semana' : 'Solo 5 por dia' !!}">
                                    <img src="holder.js/185x185/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                </a>
                        @endif
                    @endif
                </div>



            @empty
                <p>No hay publicidad para ver</p>
            @endforelse
        </div>
@stop