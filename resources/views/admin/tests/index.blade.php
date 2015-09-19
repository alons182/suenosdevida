@extends('admin.layouts.layout')

@section('content')

    <div class="starter-template">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h1>Creación de usuarios</h1>
                    {!! Form::open(['route'=>'store_users']) !!}

                    <div class="form-group">
                        {!! Form::label('cant_users', 'Cantidad de usuarios a crear:') !!}
                        {!! Form::text('cant_users', 5, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('user_id', 'Id del usuario padre:') !!}
                        {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::submit('Crear Usuarios',['class'=>'btn btn-primary'])!!}


                    {!! Form::close() !!}
                </div>
                <div class=" col-sm-6">

                    <h1>Creación de pagos</h1>
                    {!! Form::open(['route'=>'store_payments']) !!}

                    <div class="form-group">
                        {!! Form::label('cant_users', 'Del usuario:') !!}
                        {!! Form::text('to', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('user_id', 'Al usuario:') !!}
                        {!! Form::text('from', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::submit('Crear Pagos',['class'=>'btn btn-primary'])!!}


                    {!! Form::close() !!}

                    <h1>Corte mensual</h1>
                    {!! Form::open(['route'=>'generate_cut']) !!}


                        {!! Form::submit('Generar corte',['class'=>'btn btn-primary'])!!}


                    {!! Form::close() !!}

                    <h1>Corte Anual Automático</h1>
                    {!! Form::open(['route'=>'generate_charge']) !!}


                    {!! Form::submit('Generar corte',['class'=>'btn btn-primary'])!!}


                    {!! Form::close() !!}

                </div>
            </div>
            <hr>

           <div class="row">
               <div class="well filtros">


                   {!! Form::open(['route' => 'store.admin.tests.index','method' => 'get']) !!}
                   <div class="form-group">
                       <div class="controls">
                           {!! Form::label('q', 'Buscar Usuario') !!}
                           {!! Form::text('q',$search, ['class'=>'form-control'] ) !!}
                       </div>
                       <div class="controls">
                           {!! Form::label('active', 'Estado') !!}
                           {!! Form::select('active', ['' => '-- Seleccionar --','0' => 'Inactivo','1' => 'Activo'],
                           $selectedStatus, ['class'=>'form-control'] ) !!}
                       </div>
                       <div class="controls">
                           {!! Form::label('parent', 'Buscar por id del patrocinador') !!}
                           {!! Form::text('parent',$parent, ['class'=>'form-control'] ) !!}
                       </div>
                       <div class="controls">
                           <button type="submit" class="btn btn-default ">Buscar</button>
                       </div>
                   </div>
                   {!! Form::close() !!}


               </div>
           </div>

        </div>
    </div>





    <div class="table-responsive">

        <table class="table table-striped  ">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Patrocinador</th>
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
                    <td>{!! ($user->parent) ? $user->parent->username : 'No tiene patrocinador' !!} - id: {!!
                        $user->parent_id !!}
                    </td>

                    <td>{!! $user->created_at !!}</td>
                    <td>

                        {!! Form::open(['route' => ['store.admin.tests.destroy', $user->id ], 'method' => 'delete',
                        'data-confirm' => 'Estas seguro?']) !!}
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        {!! Form::close() !!}


                    </td>

                </tr>
            @endforeach
            </tbody>
            <tfoot>

            @if ($users)
                <td colspan="10" class="pagination-container">{!!$users->appends(['q' => $search])->render()!!}</td>
            @endif

            </tfoot>
        </table>
    </div>

    {!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop