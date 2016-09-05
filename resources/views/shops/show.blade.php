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


    </div>
    <div class="clear"></div>
    <div class="shop-categories">
        <h1>Categorias</h1>
        @include('layouts.partials._list_categories', ['categories' => $categories])
    </div>

</div>
@stop

