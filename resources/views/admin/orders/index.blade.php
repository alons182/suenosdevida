@extends('admin.layouts.layout')

@section('content') 
     
    
   @include('admin/orders/partials/_search')
   
	
	<div class="table-responsive">

        
        {!! Form::open(['route' =>['destroy_multiple_orders'],'method' => 'post', 'id' =>'form-delete-chk','data-confirm' => 'Estas seguro?']) !!}
        <button type="submit" class="delete-multiple btn btn-danger btn-sm "><i class="glyphicon glyphicon-trash"></i></button>     
        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>=</th>
                <th>#</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Creado</th>

                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)

                <tr>
                    <td>{!! Form::checkbox('chk_order[]', $order->id, null, ['class' => 'chk-product']) !!}</td>
                    <td>{!! $order->id !!}</td>
                    <td>{!! link_to_route('store.admin.orders.edit', $order->users->username, $order->id) !!}</td>
                    <td>{!! str_limit($order->description, 20) !!}</td>
                    <td>{!! money($order->total, '&cent') !!}</td>
                    <td>{!! $order->present()->status !!}</td>
                    <td>{!! $order->created_at !!}</td>

                    <td>
                     @if($currentUser->hasrole('administrator'))
                       <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route('store.admin.orders.destroy', [$order->id]) !!}">Eliminar</button>
                     @endif
                    </td>
                    
                </tr>
                
            @endforeach
        </tbody>
       <tfoot>
           
            @if ($orders)
                <td  colspan="10" class="pagination-container">{!!$orders->appends(['q' => $search])->render()!!}</td>
                 @endif 
            
             
        </tfoot>
    </table>
    {!! Form::close() !!}
     
    </div>  

{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop