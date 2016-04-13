@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | Shops
@stop
@section('content')
    @include('layouts/partials/_banner')
    <section class="main">
        <div class="products">
            <h1>Tiendas Disponibles </h1>
            @forelse($shops as $shop)
                <div class="product">
                    <figure class="img">
                        @if($shop->image)
                            <a href="{!! URL::route('shop_path', $shop->id) !!}"><img src="{!! photos_path('shops') !!}/thumb_{!! $shop->image !!}" alt="{!! $shop->name !!}" width="200" height="145" /></a>
                        @else
                            <a href="{!! URL::route('shop_path', $shop->id) !!}"><img src="holder.js/189x145/text:No-image" alt="{!! $shop->name !!}" width="200" height="145" /></a>
                        @endif
                    </figure>
                    <div class="min-description">
                        {!! $shop->name !!}
                    </div>


                </div>
            @empty
                <p>No hay Tiendas</p>
            @endforelse

        </div>
        <div class="featured-products">
            <h1>Productos Destacados</h1>
            @include('layouts.partials._list_products',['selected' => '' ])
        </div>
    </section>
@stop