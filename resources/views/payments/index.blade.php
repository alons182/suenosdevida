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
                    <th>Mes</th>
                    <th>Año</th>
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
                        <td> {!! $payment->month !!}</td>
                        <td> {!! $payment->year !!}</td>

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
                    <th>Mes</th>
                    <th>Año</th>
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
                        <td> {!! $payment->month !!}</td>
                        <td> {!! $payment->year !!}</td>

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



        </div>
    </section>
@stop