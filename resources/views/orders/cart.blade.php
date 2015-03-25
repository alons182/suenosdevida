@extends('layouts.layout')

@section('content')
<section class="main orders">
<h1>Carro de compras</h1>
   <div class="simpleCart_items"></div>
   <div class="simpleCart_grandTotal"></div>
   	<div class="checkoutEmptyLinks">
   				<!--Here's the Links to Checkout and Empty Cart-->
   				<a href="javascript:;" class="simpleCart_empty btn btn-purple">Vaciar carrito</a>
   				{!! link_to_route('cart_checkout','Pagar',null, ['class'=>'btn btn-purple']) !!}


   	</div>
</section>

@stop