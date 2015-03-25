@extends('layouts.layout')
@section('meta-title')
    Sueños de vida | {!! $product->name !!}
@stop
@section('content')


<div class="productdetails-view productdetails">



    <div class="product-info simpleCart_shelfItem">
        <div class="product-inner">
            <span class="item_product hidden" >{!! $product->id !!}</span>
            <h1 class="product-name item_name">{!! $product->name !!} </h1>

            <div class="clear"></div>
            @if ( $product->promo_price > 0 )
                <div class="product-price">
                    <span class="tachado">{!! money($product->price, '₡') !!}</span>
                </div>
                <div class="product-price-promo">
                    <span class="item_price">{!! money($product->promo_price, '₡') !!}</span>
                    <span class="icon icon-bookmark"><span class="discount">{!! percent($product->discount) !!}</span></span>
                </div>
            @else
                <div class="product-price">
                    <span class="item_price">{!! money($product->price, '₡') !!}</span>
                </div>
            @endif
            <div class="social-share">
                <span class='st_sharethis_large' displayText='ShareThis'></span>
                <span class='st_facebook_large' displayText='Facebook'></span>
                <span class='st_twitter_large' displayText='Tweet'></span>
                <span class='st_googleplus_large' displayText='Google +'></span>
                <span class='st_email_large' displayText='Email'></span>
                <span class='st_fblike_large' displayText='Facebook Like'></span>
            </div>
            <div class="product-description">
                <h3>Descripción</h3>

                <p>{!! $product->description !!}</p>
            </div>
            @if ( $product->present()->sizes )
            <div class="product-sizes">
                <h3>Tallas Disponibles: </h3>
                {!! Form::select('sizes', $product->present()->sizes, null , ['class'=>'form-control']) !!}
            </div>
            @endif
            @if ( $product->present()->colors )
            <div class="product-colors">
                <h3>Colores Disponibles:</h3>

                <div class="colors">
                    @foreach ($product->present()->colors as $color)
                    <div class="color" style="background-color: #{!! $color !!};"></div>
                    @endforeach
                </div>
            </div>
            @endif
           <div class="product-addCart">
            <a href="javascript:;" class="btn btn-purple item_add">Agregar al carro</a>
           </div>



             @if (count($others)>0)
            <div class="other-products">Otros Productos</div>
            <div class="related-products">
                <div class="ca-wrapper">

                    @foreach ($others as $other)
                    <div class="ca-item related-item">
                        <a href="{!! URL::route('product', [$other->categories->last()->slug, $other->slug]) !!}">
                            @if ($other->image)
                            <img src="{!! photos_path('products') !!}thumb_{!! $other->image !!}"
                                 data-src="{!! photos_path('products') !!}{!! $other->image!!}"
                                 alt="{!! $other->name !!}">
                            @else
                            <img src="holder.js/184x240/text:No-image" alt="{!! $other->name !!}">
                            @endif
                                <span class="caption">
                                    <span class="related-name">{!! $other->name !!}</span>
                                    <span class="related-price">{!! money($other->price, '&cent') !!}</span>
                                </span>

                        </a>

                    </div>
                    @endforeach

                </div>
                <div class="clear"></div>

            </div>
            @endif
        </div>
    </div>

     <div class="main-image">
            @if($product->image)
                <div class="easyzoom easyzoom--adjacent">
                    <a href="{!! photos_path('products').$product->image !!}">
                        <img src="{!! photos_path('products').'thumb_'.$product->image !!}" alt="{!! $product->name !!}" width="500"  height="400"/>
                    </a>
                </div>
            @else
                <img src="holder.js/481x531/text:No-image" alt="{!! $product->name !!}">
            @endif
             @if (count($photos)>0)

                <div class="additional-images">
                    <div class="other-image">Más imagenes</div>
                    <div class="floatleft">
                        <img src="{!! photos_path('products') !!}{!! $product->image !!}"
                             data-src="{!! photos_path('products').$product->image !!}" alt="{!! $product->name !!}"/>
                    </div>
                    @foreach ($photos as $photo)
                    <div class="floatleft">
                        <img src="{!! photos_path('products') !!}{!! $photo->product_id !!}/{!! $photo->url !!}"
                             data-src="{!! photos_path('products') !!}{!! $photo->product_id !!}/{!! $photo->url!!}"
                             alt="{!! $product->name !!}">
                    </div>
                    @endforeach


                    <div class="clear"></div>

                </div>
                @endif
        </div>

</div>
@stop
@section('scripts')
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "1ddf84c2-1fc9-49c3-8521-1c9bce02292d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
@stop
