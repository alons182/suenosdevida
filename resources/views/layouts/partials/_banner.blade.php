
    <section class="banner">

            <div class="cycle-slideshow" data-cycle-slides=".slide" data-cycle-pager="#banner-pager"  data-cycle-pager-template="<a href=#></a>">
                    <!--<div class="slide" style="background-image: url('/img/banner.jpg');">
                        <div class="info">
                          <span>Nunca desista <br/> <span class="bold purple">de un sueño.</span> </span>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('/img/banner2.jpg');">
                        <div class="info info-2">
                          <span>Si puedes soñarlo <br/> <span class="bold ">puedes hacerlo.</span> </span>
                        </div>
                    </div>
                    <div class="slide" style="background-image: url('/img/banner3.jpg');">
                        <div class="info">
                          <span>Cuando dejas de soñar <br/> <span class="bold green">dejas de vivir.</span> </span>
                        </div>

                    </div>-->
                    @foreach ($files as $file)
                        <div class="slide" style="background-image: url('/banners_files/{!! $file['name'] !!}');">

                        </div>

                    @endforeach

                    <div id="banner-pager"></div>
                </div>


    </section>

