@extends('layouts.layout')

@section('content')
    <section class="main payments">
        <h1>Balance | <small>Movimientos en tu red de afiliados</small></h1> {!! link_to_route('payments.create', 'Realizar Pago',null,['class'=>'btn btn-primary']) !!}
        {!! Form::open(['route' => 'payments.cashing', 'method' => 'post','style'=>'display:inline-block;']) !!}

            {!! Form::submit('Retirar Ganancias', ['class' => 'btn btn-orange', 'title'=>'Se envia un correo al administrador solicitando el retiro de tus fondos']) !!}

        {!! Form::close() !!}

        <div class="gains-container">
            <div class="months">
                {!! Form::open(['route' => 'payments.index', 'method' => 'get']) !!}

                <div class="form-group">
                    {!! Form::selectMonth('month', $selectedMonth, ['class' => 'form-control']) !!}

                </div>
                {!! Form::close() !!}
            </div>
            <small>Ganancias</small>
            <div class="gains">
                <h2>Pago de membresia : <span class="amount {{ ($paymentsOfMembership < 3000) ? 'red' : '' }}">{{ money($paymentsOfMembership,'₡') }}</span></h2>
                <h2>Posible: <span class="amount">{!! money($possible_gains,'₡') !!}</span></h2>
                
                <h2>Ganancia por corte : <span class="amount">{!! money($accumulatedGains,'₡') !!}</span></h2>
                <h2>Comisión : <span class="amount">{!! money($commission,'₡') !!}</span></h2>
            </div>

        </div>



        <div class="table-responsive payments-table">

            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th>Nombre Afiliado</th>
                    <th>Monto</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Tipo de pago</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($paymentsOfUserRed as $payment)
                    <tr>

                        <td>{!! $payment->id !!}</td>
                        <td>{!! $payment->users->profiles->present()->fullname !!}</td>
                        <td>{!! money($payment->amount,'₡') !!}</td>
                        <td>
                            {!! $payment->users->email !!}
                        </td>
                        <td> {!! $payment->users->profiles->telephone !!}</td>
                        <td> {!! $payment->present()->paymentType !!}</td>
                        <td> {!! $payment->description !!}</td>
                        <td> {!! $payment->created_at !!}</td>

                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align: center;">No hay movimientos en tu red de afiliados</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($paymentsOfUserRed)
                    <td  colspan="8" class="pagination-container">{!!$paymentsOfUserRed->render()!!}</td>
                @endif


                </tfoot>
            </table>


        </div>
        <h1>Tus Movimientos de pago</h1>
        <div class="table-responsive payments-table">

            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th># Transferencia</th>
                    <th>Monto</th>
                    <th>Tipo de pago</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($paymentsOfUser as $payment)
                    <tr>

                        <td>{!! $payment->id !!}</td>
                        <td>{!! $payment->transfer_number !!}</td>
                        <td>{!! money($payment->amount,'₡') !!}</td>
                        <td> {!! $payment->present()->paymentType !!}</td>
                        <td> {!! $payment->description !!}</td>
                        <td> {!! $payment->created_at !!}</td>

                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center;">No hay movimientos de pagos</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($paymentsOfUser)
                    <td  colspan="6" class="pagination-container">{!! $paymentsOfUser->render() !!}</td>
                @endif


                </tfoot>
            </table>


        </div>
        <div class="payments-ads">
            <h1>Publicidad | <small>Semana {{ $week }}: Dia: {{ $dayOfWeek  }} (Puedes ver 5 anuncios por dia)</small></h1>

            <div class="payments-ads-not-seen">


                @forelse ($ads as $ad)

                    <div class="payments-ad">
                        @if($hits_per_day != 5 && $hits_per_week != 25)

                            @if(dd($ad->hits->count()) == 0)
                                <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                    <span class="ad_id">{!! $ad->id !!}</span>
                                    @if($ad->image)
                                        <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190"/>
                                    @else
                                        <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                    @endif
                                </a>
                            @else
                                @if($ad->hits->last()->check == 0)
                                    <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                        <span class="ad_id">{!! $ad->id !!}</span>
                                        @if($ad->image)
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190"/>
                                        @else
                                            <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        @endif
                                    </a>

                                @else
                                    @if($ad->image)
                                        <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190" />
                                        </span>
                                    @else
                                        <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <span class="ad_id">{!! $ad->id !!}</span>
                                            <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        </span>
                                    @endif

                                @endif
                            @endif


                        @else
                            @if($ad->image)
                                <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($hits_per_week == 25) ? 'Has completado tus 5 dias por semana' : 'Solo 5 por dia' !!}">
                                    <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190" />
                                </span>
                            @else
                                <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($hits_per_week == 25) ? 'Has completado tus 5 dias por semana' : 'Solo 5 por dia' !!}">
                                    <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                </span>
                            @endif
                        @endif
                    </div>



                @empty
                    <p>No hay publicidad para ver</p>
                @endforelse
            </div>


        </div>
    </section>
@stop