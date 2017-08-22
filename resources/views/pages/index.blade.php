@extends('layouts.layout')

@section('content')
@include('layouts/partials/_banner')
@if(! isset($cantones))
<section class="section-top">
        <!-- cantones_path -->
    <a href="/store/nicoya/shops" class="box-info familia">
        <h1><span class="light">Tienda</span><br/> Virtual</h1>
        <span class="overlay"></span>
        <i class="icon-arrow-right"></i>
    </a>
    <a href="/aid-plan" class="box-info canasta">
        <h1><span class="light">Plan de</span><br/> Ayuda</h1>
        <span class="overlay"></span>
        <i class="icon-arrow-right"></i>
    </a>
    <a href="/ads" class="box-info ingresos">
        <span class="overlay"></span>
        <h1><span class="light">sección de </span><br/>Publicidad</h1>

        <i class="icon-arrow-right"></i>
    </a>
</section>
@endif
<section class="main">
     @if(isset($cantones))
        <h1>Seleciona un Cantón</h1>
    <div class="cantones">

        @forelse($cantones as $canton)
            <div class="canton">

                    <a href="{!! URL::route('shops_path', $canton) !!}"> {!! $canton !!}</a>

            </div>
        @empty
            <p>No hay cantones</p>
        @endforelse

    </div>
    @endif
    <div class="featured-products">
        <h1>Productos Destacados</h1>
        @include('layouts.partials._list_products',['selected' => '' ])
    </div>
</section>
@include('layouts/partials/_section_bottom')
@stop
