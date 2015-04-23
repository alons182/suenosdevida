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
                <h2>Nivel Actual: {!! $currentUser->level !!}</h2>
                <h2>Posible: <span class="amount">{!! money($possible_gains,'₡') !!}</span></h2>
                <h2>Ganada Por ver publicidad: <span class="amount">{!! money($gainsPerClick,'₡') !!}</span></h2>
                <h2>Ganancia Acumulada: <span class="amount">{!! money($accumulatedGains,'₡') !!}</span></h2>
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
                    <td  colspan="8" class="pagination-container">{!!$paymentsOfUserRed->appends(['month' => $selectedMonth])->render()!!}</td>
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
                    <td  colspan="6" class="pagination-container">{!! $paymentsOfUser->appends(['month' => $selectedMonth])->render()!!}</td>
                @endif


                </tfoot>
            </table>


        </div>
        <div class="payments-ads">
            <h1>Publicidad | <small>Semana {{ $week }} (Puedes ver 5 anuncios por mes)</small></h1>
            <div class="payments-ads-not-seen">


                @forelse ($ads as $ad)

                    <div class="payments-ad">
                        @if($hits_per_week != 5 && $possible_gains > 0)

                            @if($ad->hits->count() == 0)
                                <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                    @if($ad->image)
                                        <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190"/>
                                    @else
                                        <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                    @endif
                                </a>
                             @else
                                @if($ad->hits->last()->check == 0)
                                    <a href="{!! URL::route('ads.show', $ad->id) !!}" class="payments-ad-link">
                                        @if($ad->image)
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190"/>
                                        @else
                                            <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        @endif
                                    </a>

                                @else
                                    @if($ad->image)
                                        <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190" />
                                        </span>
                                    @else
                                        <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($ad->hits->last()) ? $ad->hits->last()->hit_date : '' !!}">
                                            <img src="holder.js/190x190/text:{!! $ad->name !!}{!! $ad->id !!}" alt="{!! $ad->name !!}">
                                        </span>
                                    @endif

                                @endif
                            @endif

                        @else
                            @if($ad->image)
                                <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($possible_gains > 0) ? 'Solo 5 por mes' : 'Aun no tienes una posible ganacia' !!}">
                                    <img src="{!! photos_path('ads').'thumb_'.$ad->image !!}" alt="{!! $ad->name !!}" width="190"  height="190" />
                                </span>
                            @else
                                <span class="payments-ad-link payments-ad-link--hit" data-msg="{!! ($possible_gains > 0) ? 'Solo 5 por mes' : 'Aun no tienes posible ganacia' !!}">
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