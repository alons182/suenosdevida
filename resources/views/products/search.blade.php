@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | Products
@stop
@section('content')
    <section class="main">
        <h1>Busqueda: {{ $search }}</h1>
        @include('layouts.partials._list_products')
        @if ($products)
           <div class="pagination-container">{{$products->appends(['q'=>$search])->render()}}</div>
       @endif
    </section>
@stop