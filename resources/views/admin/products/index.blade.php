@extends('admin.layouts.layout')

@section('content') 
     
    
   @include('admin/products/partials/_search')
   
	
	<div class="table-responsive">
        {!! link_to_route('store.admin.products.create','Nuevo Producto',null,['class'=>'btn btn-success']) !!}
        
        {!! Form::open(['route' =>['destroy_multiple'],'method' => 'post', 'id' =>'form-delete-chk','data-confirm' => 'Estas seguro?']) !!}
        <button type="submit" class="delete-multiple btn btn-danger btn-sm "><i class="glyphicon glyphicon-trash"></i></button>     
        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>=</th>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categorias</th>
                <th>Creado</th>
                <th>Publicado</th>
                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{!! Form::checkbox('chk_product[]', $product->id, null, ['class' => 'chk-product']) !!}</td>
                    <td>{!! $product->id !!}</td>
                    <td>{!! link_to_route('store.admin.products.edit', $product->name, $product->id) !!}</td>
                    <td>{!! str_limit($product->description, 20) !!}</td>
                    <td>{!! money($product->price, '&cent') !!}</td>
                    <td>
                         @foreach ($product->categories as $category)
                            {!! $category->name !!} -
                        @endforeach 
                    </td>
                    <td>{!! $product->created_at !!}</td>
                    <td>
                            
                            @if ($product->published) 
                                <button type="submit"  class="btn btn-default btn-xs" form="form-pub-unpub" formaction="{!! URL::route("products.unpub", [$product->id]) !!}"><i class="glyphicon glyphicon-ok"></i></button>
                            @else 
                                <button type="submit"  class="btn btn-default btn-xs "form="form-pub-unpub" formaction="{!! URL::route("products.pub", [$product->id]) !!}" ><i class="glyphicon glyphicon-remove"></i></button>
                            @endif

                             @if ($product->featured)
                                <button type="submit"  class="btn btn-default btn-xs" form="form-feat-unfeat" formaction="{!! URL::route("products.unfeat", [$product->id]) !!}" ><i class="glyphicon glyphicon-star"></i></button>
                            @else
                                <button type="submit"  class="btn btn-default btn-xs " form="form-feat-unfeat" formaction="{!! URL::route("products.feat", [$product->id]) !!}"><i class="glyphicon glyphicon-star-empty"></i></button>
                            @endif

                    </td>
                    <td>
                     @if($currentUser->hasrole('administrator'))
                       <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route("store.admin.products.destroy", [$product->id]) !!}">Eliminar</button>
                      @endif
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
       <tfoot>
           
            @if ($products) 
                <td  colspan="10" class="pagination-container">{!!$products->appends(['q' => $search,'cat'=>$categorySelected,'published'=>$selectedStatus])->render()!!}</td>
                 @endif 
            
             
        </tfoot>
    </table>
    {!! Form::close() !!}
     
    </div>  

{!! Form::open(array('method' => 'post', 'id' => 'form-pub-unpub')) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'post', 'id' => 'form-feat-unfeat']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop