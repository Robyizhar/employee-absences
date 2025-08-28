<section id="slider" class="slider-element dark swiper_wrapper slider-parallax min-vh-100" data-loop="true" data-nav="false" data-autoplay="5000">
    <div class="slider-inner">

        <div class="swiper-container swiper-parent">
            <div class="swiper-wrapper">
                @foreach ($data['banners'] as $banners)
                    <div class="swiper-slide dark">
                        <div class="container">
                            <div class="slider-caption">
                                <div>
                                    <h2 class="nott" data-animate="fadeInUp">{{ $banners->title }}</h2>
                                    <p class="nott" data-animate="fadeInUp">{{ $banners->description }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide-bg" style="background: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,.8)), url('{{ asset('storage/'.$banners->image) }}') no-repeat center center; background-size: cover;"></div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-navs">
                <div class="slider-arrow-left"><i class="icon-line-arrow-left"></i></div>
                <div class="slider-arrow-right"><i class="icon-line-arrow-right"></i></div>
            </div>
            <div class="swiper-scrollbar">
                <div class="swiper-scrollbar-drag">
                <div class="slide-number"><div class="slide-number-current"></div><span>/</span><div class="slide-number-total"></div></div></div>
            </div>
        </div>

    </div>
</section>