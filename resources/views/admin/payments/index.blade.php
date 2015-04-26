@extends('admin.layouts.layout')

@section('content')

@include('admin/payments/partials/_search')

<section class="main payments">

    <div class="table-responsive payments-table">
        {!! link_to_route('store.admin.payments.create','Nuevo Pago',null,['class'=>'btn btn-success']) !!}
        <table class="table table-striped  ">
            <thead>
            <tr>

                <th>#</th>
                <th>Nombre Afiliado</th>
                <th>Monto</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Tipo de pago</th>
                <th>Fecha Transf.</th>
                <th>Fecha Agregado</th>
                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($payments as $payment)
            <tr>

                <td>{!! $payment->id !!}</td>
                <td>{!! $payment->users->profiles->present()->fullname !!}</td>
                <td>
                @if($currentUser->hasrole('administrator'))
                    <a href="#" class="x-edit" data-type="text" data-name="amount" data-pk="{!! $payment->id !!}" data-url="{!! URL::route('store.admin.payments.update', [$payment->id]) !!}" data-title="Enter Monto">{!! money($payment->amount,'₡') !!}</a>
                @else
                    {!! money($payment->amount,'₡') !!}
                @endif
                </td>
                <td>
                   {!! $payment->users->email !!}
                </td>
                <td> {!! $payment->users->profiles->telephone !!}</td>
                <td> {!! $payment->present()->paymentType !!}</td>
                <td> {!! $payment->transfer_date !!}</td>
                <td> {!! $payment->created_at !!}</td>
                <td>
                     @if($currentUser->hasrole('administrator'))
                       <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route('store.admin.payments.destroy', [$payment->id]) !!}">Eliminar</button>
                      @endif
                </td>
            </tr>
            @empty
             <tr><td colspan="10" style="text-align: center;">No hay movimientos</td></tr>
            @endforelse
            </tbody>
            <tfoot>

            @if ($payments)
                <td  colspan="10" class="pagination-container">{!!$payments->appends(['month' => $selectedMonth])->render()!!}</td>
            @endif


            </tfoot>
        </table>


    </div>

</section>

{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}
@stop