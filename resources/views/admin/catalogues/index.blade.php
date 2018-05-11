@extends('admin.layouts.layout')

@section('content') 
     
     
   @include('admin/catalogues/partials/_search')
   	
	<div class="table-responsive">
        {!! link_to_route('store.admin.catalogues.create','Nuevo Catálogo',null,['class'=>'btn btn-success']) !!}
        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>url</th>
                <th>Tienda</th>
                <th>Creado</th>
                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($catalogues as $catalogue)
                @if(($catalogue->shop && $catalogue->shop->responsable_id == $currentUser->id) || $currentUser->hasrole('administrator'))
                <tr>
                    <td>{!! $catalogue->id !!}</td>
                    <td>
                       
                        {!! link_to_route('store.admin.catalogues.edit', $catalogue->name, $catalogue->id) !!}
                       
                    </td>
                    <td>{!!  $catalogue->url !!}</td>
                    <td>{!! ($catalogue->shop)? $catalogue->shop->name : '' !!}</td>
                    <td>{!! $catalogue->created_at !!}</td>
                    <td>
                        @if($currentUser->hasrole('administrator'))
                        <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route('store.admin.catalogues.destroy', [$catalogue->id]) !!}">Eliminar</button>
                        @endif
                         
                    </td>
                    
                </tr>
                @endif
            @endforeach
        </tbody>
       <tfoot>
         
            @if ($catalogues) 
                <td  colspan="10" class="pagination-container">{!!$catalogues->appends(['q' => $search])->render()!!}</td>
            @endif 
            
             
        </tfoot>
    </table>

     
    </div>  

{{-- This form is used for general post requests --}}
{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop