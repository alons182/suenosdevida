<footer class="Footer">
    <div class="column column-categories">
        <h2>Categorias</h2>
        <ul>
             @foreach($categories as $category)
                    <li>{!! link_to_route('products_path',$category->name,$category->slug) !!}</li>
             @endforeach

        </ul>
    </div>
    <div class="column column-info">
        <h2>Información</h2>
        <ul>
            <li><a href="/top-sellers">Los más vendidos</a></li>
            <li><a href="/aid-plan">Plan de ayuda</a></li>
            <li><a href="/contact">Contáctenos</a></li>
            <li><a href="/about">Acerca de nosotros</a></li>
            <li><a href="/terms">Términos y condiciones</a></li>

        </ul>
    </div>
    <div class="column column-account">
        <h2>Mi cuenta</h2>
        @include('layouts/partials/_navbarAccount')
    </div>
    <div class="column column-contact">
        <h2>Contáctenos</h2>
        <p><a href="mailto:info@suenosdevidacr.com">info@suenosdevidacr.com</a></p>
        <h2>Siguenos en</h2>
        <div class="redes">
            <a href="https://www.facebook.com/suenosdevidacr" target="_blank" title="Facebook" class="facebook"><i class="icon icon-facebook"></i></a>
            <a href="https://twitter.com/suenosdevidacr" target="_blank" title="Twitter" class="twitter"><i class="icon icon-twitter"></i></a>
            <a href="https://plus.google.com/109324544361115734719" rel="publisher" target="_blank" title="Google Plus" class="google"><i class="icon icon-googleplus"></i></a>
        </div>
    </div>
</footer>