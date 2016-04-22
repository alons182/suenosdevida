@extends('admin.layouts.layout')

@section('content') 
     
    
   @include('admin/ads/partials/_search')
   
	
	<div class="table-responsive">
        {!! link_to_route('store.admin.ads.create','Nuevo Anuncio',null,['class'=>'btn btn-success']) !!}
        
        {!! Form::open(['route' =>['destroy_multiple'],'method' => 'post', 'id' =>'form-delete-chk','data-confirm' => 'Estas seguro?']) !!}
        <button type="submit" class="delete-multiple btn btn-danger btn-sm "><i class="glyphicon glyphicon-trash"></i></button>     
        <table class="table table-striped  ">
        <thead>
            <tr>
                <th>=</th>
                <th>#</th>
                <th>Nombre</th>

                <th>Creado</th>
                <th>Publicado</th>
                <th><i class="glyphicon glyphicon-cog"></i></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ads as $ad)
                <tr>
                    <td>{!! Form::checkbox('chk_ad[]', $ad->id, null, ['class' => 'chk-product']) !!}</td>
                    <td>{!! $ad->id !!}</td>
                    <td>{!! link_to_route('store.admin.ads.edit', $ad->name, $ad->id) !!}</td>


                    <td>{!! $ad->created_at !!}</td>
                    <td>
                            
                            @if ($ad->published)
                                <button type="submit"  class="btn btn-default btn-xs" form="form-pub-unpub" formaction="{!! URL::route('ads.unpub', [$ad->id]) !!}"><i class="glyphicon glyphicon-ok"></i></button>
                            @else 
                                <button type="submit"  class="btn btn-default btn-xs "form="form-pub-unpub" formaction="{!! URL::route('ads.pub', [$ad->id]) !!}" ><i class="glyphicon glyphicon-remove"></i></button>
                            @endif

                             @if ($ad->featured)
                                <button type="submit"  class="btn btn-default btn-xs" form="form-feat-unfeat" formaction="{!! URL::route('ads.unfeat', [$ad->id]) !!}" ><i class="glyphicon glyphicon-star"></i></button>
                            @else
                                <button type="submit"  class="btn btn-default btn-xs " form="form-feat-unfeat" formaction="{!! URL::route('ads.feat', [$ad->id]) !!}"><i class="glyphicon glyphicon-star-empty"></i></button>
                            @endif

                    </td>
                    <td>
                     @if($currentUser->hasrole('administrator'))
                       <button type="submit" class="btn btn-danger btn-sm" form="form-delete" formaction="{!! URL::route('store.admin.ads.destroy', [$ad->id]) !!}">Eliminar</button>
                      @endif
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
       <tfoot>
           
            @if ($ads)
                <td  colspan="10" class="pagination-container">{!!$ads->appends(['q' => $search,'published'=>$selectedStatus])->render()!!}</td>
                 @endif 
            
             
        </tfoot>
    </table>
    {!! Form::close() !!}
     
    </div>  

{!! Form::open(array('method' => 'post', 'id' => 'form-pub-unpub')) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'post', 'id' => 'form-feat-unfeat']) !!}{!! Form::close() !!}
{!! Form::open(['method' => 'delete', 'id' =>'form-delete','data-confirm' => 'Estas seguro?']) !!}{!! Form::close() !!}

@stop