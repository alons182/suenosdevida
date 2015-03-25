@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | Products
@stop
@section('content')
    <section class="main">
        <h1>Categorias</h1>
        @include('layouts.partials._list_categories')
    </section>
@stop