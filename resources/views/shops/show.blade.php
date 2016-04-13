@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | {!! $shop->name !!}
@stop
@section('content')


<div class="shops-details">



    <div class="shop-info">

        <h1 class="shop-name item_name">{!! $shop->name !!} </h1>
        <div class="shop-logo" style="background-image: url('{!! photos_path('shops').'thumb_'.$shop->logo !!}')">
        </div>

        <div class="shop-information">
            {!! $shop->information !!}
        </div>
        <div class="shop-image" style="background-image: url('{!! photos_path('shops').'thumb_'.$shop->image !!}')">
        </div>
        <div class="shop-detais">
            {!! $shop->details !!}
        </div>


    </div>
    <div class="clear"></div>
    <div class="shop-categories">
        <h1>Categorias</h1>
        @include('layouts.partials._list_categories', ['categories' => $categories])
    </div>

</div>
@stop

