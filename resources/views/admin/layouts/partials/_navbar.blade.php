 
 <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Sueños de vida</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="{!! set_active('admin') !!}">{!!  link_to_route('dashboard','Inicio')  !!}</li>
           @if (! Auth::guest())
            @if ($currentUser->hasRole('administrator') || $currentUser->hasRole('subadministrator'))
                <li class="{!! set_active('admin/users') !!}">{!! link_to_route('store.admin.users.index','Usuarios') !!}</li>
                <li class="{!! set_active('admin/downloads') !!}">{!! link_to_route('store.admin.downloads.index','Downloads') !!}</li>
                <li class="{!! set_active('admin/banners') !!}">{!! link_to_route('store.admin.banners.index','Banners') !!}</li>
                <li class="{!! set_active('admin/payments') !!}">{!! link_to_route('store.admin.payments.index','Pagos') !!}</li>
                <li class="{!! set_active('admin/ads') !!}">{!! link_to_route('store.admin.ads.index','Publicidad') !!}</li>
                <li class="{!! set_active('admin/test') !!}">{!! link_to_route('store.admin.tests.index','Test') !!}</li>
                <!-- <li class="{!! set_active('admin/shops') !!}">{!! link_to_route('store.admin.shops.index','Tiendas') !!}</li> -->
            @endif
  
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tienda<span class="caret"></span></a>
                <ul class="dropdown-menu">
                  @if ($currentUser->hasRole('administrator') || $currentUser->hasRole('subadministrator'))
                   <li class="{!! set_active('admin/shops') !!}">{!! link_to_route('store.admin.shops.index','Tiendas') !!}</li>
                  @endif 
                  <li class="{!! set_active('admin/catalogues') !!}">{!! link_to_route('store.admin.catalogues.index','Catálogos') !!}</li>
                  <li class="{!! set_active('admin/categories') !!}">{!! link_to_route('store.admin.categories.index','Categorias') !!}</li>
                  <li class="{!! set_active('admin/products') !!}">{!! link_to_route('store.admin.products.index','Productos') !!}</li>
                  <li class="{!! set_active('admin/orders') !!}">{!! link_to_route('store.admin.orders.index','Ordenes') !!}</li>

                </ul>
            </li>
            
            <li>{!! link_to_route('logout','Logout') !!}</li>
           @else
              <li class="{!! set_active('admin/login') !!}">{!! link_to_route('login','Login') !!}</li>

            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
