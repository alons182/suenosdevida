@extends('layouts.layout')

@section('content')
<section class="main payments">
    <h1>Balance | <small>Movimientos en tu red de afiliados</small></h1> {!! link_to_route('payments.create', 'Realizar Pago',null,['class'=>'btn btn-primary']) !!}


    <div class="gains-container">
        <div class="months">
            {!! Form::open(['route' => 'payments.index', 'method' => 'get']) !!}
            <!-- Mes Form Input -->
            <div class="form-group">
                {!! Form::selectMonth('month', $selectedMonth, ['class' => 'form-control']) !!}

            </div>
            {!! Form::close() !!}
        </div>
        <small>Ganancias</small>
        <div class="gains">
            <h2>Pago de membresia : <span class="amount {!! ($payments['totalPaymentOfUser'] < 12000) ? 'red' : '' !!}">{!! money($payments['totalPaymentOfUser'],'₡') !!}</span></h2>
            <h2>Bruta : <span class="amount">{!! money($payments->first(),'₡') !!}</span> </h2>
            <h2>Posible : <span class="amount">{!! money($payments['possible_gain'],'₡') !!}</span></h2>
            <h2>Neta (Membresia mensual) : <span class="amount">{!! money($payments['gain_neta'],'₡') !!}</span></h2>
        </div>

    </div>



    <div class="table-responsive payments-table">

        <table class="table table-striped  ">
            <thead>
            <tr>

                <th>#</th>
                <th>Nombre Afiliado</th>
                <th>Monto</th>
                <th>Ganancia Posible</th>
                <th>Ganancia</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Tipo de pago</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($payments['payments'] as $payment)
            <tr>

                <td>{!! $payment->id !!}</td>
                <td>{!! $payment->users->profiles->present()->fullname !!}</td>
                <td>{!! money($payment->amount,'₡') !!}</td>
                <td>{!! money($payment->possible_gain,'₡') !!}</td>
                <td>{!! money($payment->gain,'₡') !!}</td>
                <td>
                   {!! $payment->users->email !!}
                </td>
                <td> {!! $payment->users->profiles->telephone !!}</td>
                <td> {!! $payment->present()->paymentType !!}</td>
                <td> {!! $payment->created_at !!}</td>

            </tr>
            @empty
             <tr><td colspan="9" style="text-align: center;">No hay movimientos en tu red de afiliados</td></tr>
            @endforelse
            </tbody>
            <tfoot>

            @if ($payments['payments'])
                <td  colspan="8" class="pagination-container">{!!$payments['payments']->appends(['month' => $selectedMonth])->render()!!}</td>
            @endif


            </tfoot>
        </table>


    </div>
    <h1><small>Tus Movimientos de pago</small></h1>
    <div class="table-responsive payments-table">

            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th># Transferencia</th>
                    <th>Monto</th>
                    <th>Tipo de pago</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($payments['paymentsOfUser'] as $payment)
                <tr>

                    <td>{!! $payment->id !!}</td>
                    <td>{!! $payment->transfer_number !!}</td>
                    <td>{!! money($payment->amount,'₡') !!}</td>
                    <td> {!! $payment->present()->paymentType !!}</td>
                    <td> {!! $payment->created_at !!}</td>

                </tr>
                @empty
                 <tr><td colspan="5" style="text-align: center;">No hay movimientos de pagos</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($payments['paymentsOfUser'])
                    <td  colspan="5" class="pagination-container">{!!$payments['paymentsOfUser']->appends(['month' => $selectedMonth])->render()!!}</td>
                @endif


                </tfoot>
            </table>


        </div>
    <div class="payments-ads">
        @forelse ($ads as $ad)

                <div class="payments-ad">
                    @if($ad->hits->count() == 0)
                        <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                            @if($ad->image)
                                <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="200"  height="200"/>
                            @else
                                <img src="holder.js/200x200/text:{!! $ad->name !!}" alt="{!! $ad->name !!}">
                            @endif
                        </a>
                    @else
                        @if($ad->image)
                            <span class="payments-ad-link payments-ad-link--hit" data-msg="Publicidad Vista">
                                <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="200"  height="200" />
                            </span>
                        @else
                            <span class="payments-ad-link payments-ad-link--hit" data-msg="Publicidad Vista">
                                <img src="holder.js/200x200/text:{!! $ad->name !!}" alt="{!! $ad->name !!}">
                            </span>
                        @endif
                    @endif
                </div>



        @empty
            <p>No hay publicidad para ver</p>
        @endforelse
    </div
</section>

@stop