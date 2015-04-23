@extends('admin.layouts.layout')

@section('content')
<div class="starter-template">
	<h1>Editar Usuario</h1>
	{!! Form::model($user, ['method' => 'put', 'route' => ['store.admin.users.update', $user->id] ]) !!}
		 @include('admin/users/partials/_form',['buttonText' => 'Actualizar Usuario'])
	{!! Form::close() !!}
    <div class="col-sm-6">
        <div class="table-responsive hits-table">

            <table class="table table-striped  ">
                <thead>
                <tr>

                    <th>#</th>
                    <th>Publicidad Vista</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($hits as $hit)
                    <tr>

                        <td>{!! $hit->id !!}</td>
                        <td>{!! $hit->ad->name !!}</td>
                        <td> {!! $hit->hit_date !!}</td>

                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center;">No ha visto ningun anuncio todavia</td></tr>
                @endforelse
                </tbody>
                <tfoot>

                @if ($hits)
                    <td  colspan="3" class="pagination-container">{!!$hits->render()!!}</td>
                @endif


                </tfoot>
            </table>


        </div>
    </div>

</div>
@stop