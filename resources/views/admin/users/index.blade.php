@extends('admin.layouts.layout')

@section('content') 
     
     @include('admin/users/partials/_search')

	<div class="table-responsive">
        @if($currentUser->hasrole('administrator'))
        {!! link_to_route('user_register','Nuevo Usuario',null,['class'=>'btn btn-success']) !!}
        @endif

        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Patrocinador</th>
                <th>Tipo</th>
                <th>Creado</th>
                <th><i class="icon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{!! $user->id !!}</td>
                    <td>{!! link_to_route('store.admin.users.edit', $user->username, $user->id) !!}
                    <td>{!! $user->email !!}</td>
                    <td>{!! ($user->parent) ? $user->parent->username : 'No tiene patrocinador' !!}</td>
                    <td>{!! $user->roles->first()->name !!}</td>
                    <td>{!! $user->created_at !!}</td>
                    <td>
                     @if($currentUser->hasrole('administrator'))
                         @if ($user->active)
                            <button type="submit"  class="btn btn-success btn-xs" form="form-active-inactive" formaction="{!! URL::route('users.inactive', [$user->id]) !!}">Activo <i class="glyphicon glyphicon-ok"></i></button>
                        @else
                            <button type="submit"  class="btn btn-danger btn-xs "form="form-active-inactive" formaction="{!! URL::route('users.active', [$user->id]) !!}" > Inactivo <i class="glyphicon glyphicon-remove"></i></button>
                        @endif
                     @endif
                    <!--{!! Form::open(['route' => ['store.admin.users.destroy', $user->id ], 'method' => 'delete', 'data-confirm' => 'Estas seguro?']) !!}
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    {!! Form::close() !!}-->


                        
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
       <tfoot>

             @if ($users) 
                <td  colspan="10" class="pagination-container">{!!$users->appends(['q' => $search])->render()!!}</td>
            @endif 
             
        </tfoot>
    </table>
    </div>  

{!! Form::open(array('method' => 'post', 'id' => 'form-active-inactive')) !!}{!! Form::close() !!}
@stop