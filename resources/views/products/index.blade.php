@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | Products
@stop
@section('content')
    <section class="main">
        <h1>{{ (isset($category)) ? $category : 'Busqueda' }}</h1>
        @include('layouts.partials._filter_products')
        @include('layouts.partials._list_products')
        @if ($products)
           <div class="pagination-container">{{$products->appends(['subcat'=>$selected])->render()}}</div>
       @endif
    </section>
@stop