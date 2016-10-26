@extends('layouts.layout')

@section('content')
    <div class="payments-ads">
        <h1>Seleciona un Cantón</h1>
        <div class="payments-ads-types">

            <div class="payments-ads-type">

                <a href="{!! URL::route('ads_type', 1) !!}"> Video</a>


            </div>
            <div class="payments-ads-type">
                <a href="{!! URL::route('ads_type', 2) !!}"> Sitio Web</a>
            </div>
        </div>
    </div>

@stop