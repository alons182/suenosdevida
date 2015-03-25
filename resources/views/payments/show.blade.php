@extends('layouts.layout')

@section('content')
<section class="main payments">
    <h1>Balance</h1> {{ link_to_route('payments.create', 'Realizar Pago') }}
</section>

@stop