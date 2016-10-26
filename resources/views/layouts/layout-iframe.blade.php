
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>@yield('meta-title','Sueños de Vida')</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="{{ elixir('css/bundle.css') }}" />
</head>

<body class="layout-iframe">
    <aside class="header-top">
        <div class="inner">
            @if (Auth::guest())
                <a href="#" class="btn-login"><i class="icon-arrow-right"></i>Registrarse | login</a>
                <div class="login-register">
                    @include('layouts/partials/_login')
                <div>
            @else
                <i class="icon-arrow-right"></i>{!! link_to_route('profile.edit', 'Perfil', Auth::user()->username)  !!} |
                <i class="icon-arrow-right"></i>{!! link_to_route('logout', 'Logout', null, ['class'=>'btn-logout']) !!} |
                <span class="HeaderTop-info">Welcome, {!!  link_to_route('profile.edit', Auth::user()->username, Auth::user()->username)  !!}</span>
            @endif
                <a href="/" class="logo logo-iframe"><img src="/img/logo.png" alt="Sueños de vida"/></a>
        </div>

    </aside>

    @include('layouts/partials/_navbarSite')


    <section class="wrapps">

        @include('flash::message')
        @yield('content')


    </section>

    <aside class="copyright">
        <div class="inner">
            <a href="http://www.avotz.com" class="avotz" target="_blank"><i class="icon-avotz"></i></a> &copy; 2015 Sueños de Vida

        </div>
    </aside>


    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!--<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.min.js"><\/script>')</script>-->
    <!--<script src="/js/simpleCart.min.js"></script>-->
    <script src="{{ elixir('js/bundle.js') }}"></script>
    @yield('scripts')

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
        /* (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
         function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
         e=o.createElement(i);r=o.getElementsByTagName(i)[0];
         e.src='//www.google-analytics.com/analytics.js';
         r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
         ga('create','UA-XXXXX-X');ga('send','pageview');*/
    </script>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</body>
</html>
