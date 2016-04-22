@extends('admin.layouts.layout')

@section('content')
	<div class="row dashboard">
        <div class="col-md-4">
          <h2>Últimas Categorias </h2>
         <div class="list-group">
		   @foreach ($categories as $category)
		       <a href="{!! URL::route('store.admin.categories.edit', $category->id) !!}" class="list-group-item">{!! $category->name !!} <span class="badge">{!! $category->products_count !!}</span></a>
		   @endforeach
		 
		</div>
          <p><a class="btn btn-primary" href="/store/admin/categories" role="button">Ver todas &raquo; <span class="badge">{!! $tc-1 !!}</span></a></p>
        </div>
        <div class="col-md-4">
          <h2>Últimos Productos</h2>
          <div class="list-group">
		   @foreach ($products as $product)
			   {!!  link_to_route('store.admin.products.edit', $product->name, $product->id,['class'=> 'list-group-item']) !!}
		   @endforeach
		 
		 </div>
          <p><a class="btn btn-primary" href="/store/admin/products" role="button">Ver todos &raquo; <span class="badge">{!! $tp !!}</span></a></p>
       </div>
       <div class="col-md-4">
         <h2>Últimas Ordenes</h2>
         <div class="list-group">
           @foreach ($orders as $order)
               {!!  link_to_route('store.admin.orders.edit', 'Orden #'.$order->id, $order->id,['class'=> 'list-group-item']) !!}
           @endforeach

         </div>
         <p><a class="btn btn-primary" href="/store/admin/orders" role="button">Ver todas &raquo; <span class="badge">{!! $to !!}</span></a></p>
      </div>
        @if($currentUser->hasrole('administrator'))
        <div class="col-md-4">
          <h2>Últimos Usuarios</h2>
          <div class="list-group">
		   @foreach ($users as $user)
			   {!!  link_to_route('store.admin.users.edit', $user->username, $user->id,['class'=> 'list-group-item']) !!}
		   @endforeach
		 
		 </div>
          <p><a class="btn btn-primary" href="/store/admin/users" role="button">Ver todos &raquo; <span class="badge">{!! $tu !!}</span></a></p>
        </div>
        @endif
   </div>
@stop