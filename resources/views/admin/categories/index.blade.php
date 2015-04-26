@extends('admin.layouts.layout')

@section('content') 
     
     
   @include('admin/categories/partials/_search')
   	
	<div class="table-responsive">
        {!! link_to_route('store.admin.categories.create','Nueva Categoria',null,['class'=>'btn btn-success']) !!}
        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Creado</th>
                <th>Pub | Des</th>
                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{!! $category->id !!}</td>
                    <td>
                        @if ($category->depth > 0 )
                            {!! get_depth($category->depth)!!} {!!  link_to_route('store.admin.categories.edit', $category->name, $category->id) !!}
                        @else 
                            {!! link_to_route('store.admin.categories.edit', $category->name, $category->id) !!}
                        @endif
                    </td>
                    <td>{!! str_limit($category->description, 20) !!}</td>
                    <td>{!! $category->created_at !!}</td>
                    <td>
     
                            @if ($category->published) 
                                <button type="submit"  class="btn btn-default btn-xs" form="form-pub-unpub" formaction="{!! URL::route('categories.unpub', [$category->id]) !!}"><i class="glyphicon glyphicon-ok"></i></button>
                            @else 
                                <button type="submit"  class="btn btn-default btn-xs " form="form-pub-unpub" formaction="{!! URL::route('categories.pub', [$category->id]) !!}"><i class="glyphicon glyphicon-remove"></i></button>
                            @endif 
            
                            
                            @if ($category->featured) 
                                <button type="submit"  class="btn btn-default btn-xs" form="form-feat-unfeat" formaction="{!! URL::route('categories.unfeat', [$category->id]) !!}" ><i class="glyphicon glyphicon-star"></i></button>
                            @else 
                                <button type="submit"  class="btn btn-default btn-xs " form="form-feat-unfeat" formaction="{!! URL::route('categories.feat', [$category->id]) !!}"><i class="glyphicon glyphicon-star-empty"></i></button>
                            @endif 
                             
                           
                    </td>
                    
                    <td>
                        @if($currentUser->hasrole('administrator'))
                        <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route('store.admin.categories.destroy', [$category->id]) !!}">Eliminar</button>
                   @endif
                         
                    @if ($category->isRoot())
                       
                    @else
                        <div class="btn-group actions">
                        @foreach (array('up', 'down') as $key)
                            <button class="btn btn-xs btn-link" type="submit" title="Move {!!$key!!}" form="form-up-down" formaction="{!! URL::route('categories.$key', array($category->id)) !!}">
                               <i class="glyphicon glyphicon-chevron-{!! $key !!}"></i>
                            </button>
                        @endforeach

                           
                        </div>
                    @endif
                        
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
       <tfoot>
         
            @if ($categories) 
                <td  colspan="10" class="pagination-container">{!!$categories->appends(['q' => $search,'published'=>$selectedStatus])->render()!!}</td>
            @endif 
            
             
        </tfoot>
    </table>

     
    </div>  

{{-- This form is used for general post requests --}}
{!! Form::open(['method' => 'post', 'id' => 'form-up-down']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'post', 'id' => 'form-pub-unpub']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'post', 'id' => 'form-feat-unfeat']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop