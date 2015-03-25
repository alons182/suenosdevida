@extends('layouts.layout')

@section('content')
    <section class="main register">
        <h1>Datos de pago</h1>
        <div class="col-1">
            {!! Form::open(['route' => 'cart_checkout']) !!}
             <!-- First name Form Input -->
            <ul>
            @foreach($data as $item)
                <li>{!! $item !!}</li>
            @endforeach
             </ul>


            {!! Form::close() !!}
        </div>
        <div class="simpleCart_items"></div>
         <div class="simpleCart_grandTotal"></div>
         <!-- Create Account Form Input -->

         <div class="col-2">
             <a href="javascript:;" class="simpleCart_checkout btn btn-purple">Pagar</a>

         </div>

    </section>
@stop

