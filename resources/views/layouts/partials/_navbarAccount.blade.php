<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if (! $currentUser)
                    <li>{!! link_to_route('registration.create','Registrate') !!}</li>
                    <li>{!! link_to_route('sessions.create','Login') !!}</li>
                    <li><a href="#">Tienda</a></li>
                @else
                    <li>{!! link_to_route('profile.edit', 'Editar Mi Perfil',$currentUser->username) !!}</li>
                    <li>{!! link_to_route('red.show', 'Mi Red') !!}</li>
                    <li>{!! link_to_orders() !!}</li>
                    <li>{!! link_to_payments('Balance | Pagos') !!}</li>
                    <li><a href="/logout">Logout</a></li>
                @endif
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>