<div class="form-shopReply">
    @if($shop->products->count())
        <div class="alert alert-warning">Esta tienda ya contiene productos ({{ $shop->products->count() }})!!</div>
    @endif
    <p>Replicar productos y categorias de la tienda...</p>
    <div class="filtros">

        {!! Form::open(['route'=>'shops_reply','files'=> true]) !!}
        {!! Form::hidden('currentShop', isset($shop) ? $shop->id : null, ['class' => 'form-control']) !!}
        {!! Form::hidden('countProducts', isset($shop) ? $shop->products->count() : null, ['class' => 'form-control']) !!}

        <div class="form-group">
            <div class="controls">
                {!! Form::select('shopToReply', ['' => '-- Tiendas --'] + $shops, null, ['class'=>'form-control', 'required' => 'required'] ) !!}
                {!! Form::checkbox('productsReply', '1', true) !!} Products
                {!! Form::checkbox('categoriesReply', '1', true) !!} Categories
             </div>
             <div class="controls">
                 {!! Form::submit('Replicar',['class'=>'btn btn-primary'])!!}
                <a href="#" class="btn btn-default btn-reply-cancel">Cancelar</a>
            </div>

        </div>

        {!! Form::close() !!}
    </div>

</div>
<div class="clear"></div>
