@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Order #{!! $order->id !!} | Total: {!! money($order->total,'₡') !!}</h1>


	{!! Form::model($order, ['method' => 'put', 'route' => ['store.admin.orders.update', $order->id],'class'=>'form-inline']) !!}

      <div class="form-group">
      			{!! Form::label('status','Estado:')!!}
      			{!! Form::select('status', ['P' => 'Pendiente', 'F' => 'Finalizada'], $order->status,['class'=>'form-control','required'=>'required']) !!}
      			{!! errors_for('status',$errors) !!}

      </div>
      @if($currentUser->hasrole('administrator') || $currentUser->hasrole('tienda'))
        {!! Form::submit('Actualizar',['class'=>'btn btn-primary'])!!}
      @endif
     {!! link_to_route('orders', 'Regresar', null, ['class'=>'btn btn-default'])!!}

    {!! Form::close() !!}
        <h2>Articulos</h2>
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

                            <td>{!! $detail->id !!}</td>
                            <td>{!! $detail->products->name !!}</td>
                            <td>{!! $detail->quantity !!}</td>
                            <td>{!! money($detail->products->price,'₡') !!}</td>


                        </tr>
                        @empty
                         <tr><td colspan="8" style="text-align: center;">No hay productos registrados en la orden</td></tr>
                        @endforelse
                        </tbody>
                        <tfoot>




                        </tfoot>
                    </table>


                </div>
	 

</div>
@stop