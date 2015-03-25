<div class="products">
       @forelse($categories as $category)
            <div class="product">
                   <figure class="img">
                       @if($category->image)
                            <a href="{!! URL::route('products_path', $category->slug) !!}"><img src="{!! photos_path('categories') !!}/thumb_{!! $category->image !!}" alt="{!! $category->name !!}" width="200" height="145" /></a>
                       @else
                           <a href="{!! URL::route('products_path', $category->slug) !!}"><img src="holder.js/189x145/text:No-image" alt="{!! $category->name !!}" width="200" height="145" /></a>
                       @endif
                   </figure>
                   <div class="min-description">
                       {!! $category->name !!}
                   </div>


           </div>
       @empty
        <p>No hay categorias</p>
       @endforelse

</div>