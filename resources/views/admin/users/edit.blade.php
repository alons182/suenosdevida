@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Usuario</h1>
	{!! Form::model($user, ['method' => 'put', 'route' => ['store.admin.users.update', $user->id] ]) !!}
		 @include('admin/users/partials/_form',['buttonText' => 'Actualizar Usuario'])
	{!! Form::close() !!}
    <div class="col-sm-6">
        <div class="table-responsive hits-table">
            <h3>Publicidad Vista</h3>
            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th>Anuncio</th>
                    <th>Fecha</th>
                    <th><i class="glyphicon glyphicon-cog"></i></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($hits as $hit)
                    <tr>

                        <td>{!! $hit->id !!}</td>
                        <td>{!! $hit->ad->name !!}</td>
                        <td> {!! $hit->created_at !!}</td>
                        <td>
                            @if($currentUser->hasrole('administrator'))
                                <button type="submit" class="btn btn-danger btn-sm" form="form-delete-hits" formaction="{!! URL::route('store.admin.hits.destroy', [$hit->id]) !!}">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align: center;">No ha visto ningun anuncio todavia</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($hits)
                    <td  colspan="4" class="pagination-container">{!!$hits->render()!!}</td>
                @endif


                </tfoot>
            </table>


        </div>
        <div class="table-responsive gains-table">
            <h3>Ganancias</h3>
            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th><i class="glyphicon glyphicon-cog"></i></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($gains as $gain)
                    <tr>

                        <td>{!! $gain->id !!}</td>
                        <td>{!! $gain->description !!}</td>
                        <td> {!! money($gain->amount,'₡') !!}</td>
                        <td> {!! $gain->created_at !!}</td>
                        <td>
                            @if($currentUser->hasrole('administrator'))
                                <button type="submit" class="btn btn-danger btn-sm" form="form-delete-gains" formaction="{!! URL::route("store.admin.gains.destroy", [$gain->id]) !!}">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center;">No Ganancias por corte</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($gains)
                    <td  colspan="5" class="pagination-container">{!!$gains->render()!!}</td>
                @endif


                </tfoot>
            </table>

            <button type="submit" class="btn btn-danger btn-sm" form="form-annual-charge" formaction="{!! URL::route('users.annual_charge', [$user->id]) !!}">Cobro Anual</button>
        </div>
    </div>

</div>
{!! Form::open(['method' => 'delete', 'id' =>'form-delete-gains','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'delete', 'id' =>'form-delete-hits','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}
{!! Form::open(array('method' => 'post', 'id' => 'form-annual-charge')) !!}{!! Form::close() !!}
@stop