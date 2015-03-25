@extends('layouts.layout')

@section('content')
<section class="main payments">
    <h1>Historial de ordenes</h1>

    <div class="table-responsive orders-table">

        <table class="table table-striped  ">
            <thead>
            <tr>

                <th>#</th>
                <th>Detalle</th>
                <th>Descripción</th>
                <th>Total</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
            <tr>

                <td>{!! $order->id !!}</td>
                <td>{!! link_to_route('orders.show', 'Ver',$order->id) !!}</td>
                <td>{!! $order->description !!}</td>
                <td>{!! money($order->total,'₡') !!}</td>
                <td> {!! $order->created_at !!}</td>

            </tr>
            @empty
             <tr><td colspan="8" style="text-align: center;">No hay ordenes registradas</td></tr>
            @endforelse
            </tbody>
            <tfoot>

            @if ($orders)
                <td  colspan="7" class="pagination-container">{!!$orders->render()!!}</td>
            @endif


            </tfoot>
        </table>



        {!! Form::text('success',Session::get('success')) !!}

    </div>
</section>

@stop
@section('scripts')
    <script>
        (function($) {
            var orderState = $('input[name=success]').val();
            if(orderState != '')
            {
                localStorage.clear();
                //orderState = '';
                $('input[name=success]').val('');
            }
            console.log(orderState);
        })(jQuery);
    </script>
@stop