<div class="products">
       @forelse($products as $product)
            <div class="product simpleCart_shelfItem">
                   <figure class="img">
                       @if($product->image)
                            <a href="{!! URL::route('product_path', [$product->categories->last()->slug, $product->slug]) !!}"><img src="{!! photos_path('products') !!}/thumb_{!! $product->image !!}" alt="{!! $product->name !!}" width="200" height="145" /></a>
                       @else
                           <a href="{!! URL::route('product_path', [$product->categories->last()->slug, $product->slug]) !!}"><img src="holder.js/189x145/text:No-image" alt="{!! $product->name !!}" width="200" height="145" /></a>
                       @endif
                   </figure>
                   <div class="min-description item_name">
                       {!! $product->name !!}
                   </div>
                   <div class="price item_price">
                       {!! money($product->price, '₡') !!}
                   </div>
                   {!! link_to_route('product_path','Ver detalles',[$product->categories->last()->slug, $product->slug],['class' => 'btn btn-purple'] )!!}

           </div>

       @empty
        <p>No se encontraron articulos</p>
       @endforelse


</div>