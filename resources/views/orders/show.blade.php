@extends('layouts.layout')

@section('content')
<section class="main orders">
    <h1>Order #{{ $order->id }}</h1>

    <div class="table-responsive orders-table">

            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>precio</th>

                </tr>
                </thead>
                <tbody>
                @forelse ($order->details as $detail)
                <tr>

                    <td>{{ $detail->id }}</td>
                    <td>{{ $detail->products->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ money($detail->products->price,'₡') }}</td>


                </tr>
                @empty
                 <tr><td colspan="8" style="text-align: center;">No hay productos registrados en la orden</td></tr>
                @endforelse
                </tbody>
                <tfoot>




                </tfoot>
            </table>


        </div>
</section>

@stop