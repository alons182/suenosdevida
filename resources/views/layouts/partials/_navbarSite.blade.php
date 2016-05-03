<button class="btn-menu">Menu</button>
<nav class="menu">
    <ul class="inner">
        <li> <a href="/">Inicio</a> </li>
        <li> <a href="/about">Acerca de Nosotros</a></li>
        <li> <a href="/opportunity">Oportunidad</a></li>
        @if($currentUser)
            <li> {!! link_to_payments('Balance') !!}</li>
        @endif
        <li> <a href="/contact">Contacto</a></li>
        <li class="store parent"> <span>Tienda</span>
            <ul class="sub-menu">
               @foreach($shops as $shop)
                   <li>{!! link_to_route('shop_path',$shop->name.' ('. $shop->canton.')',$shop->id) !!}</li>
               @endforeach
            </ul>
        </li>
    </ul>
</nav>
