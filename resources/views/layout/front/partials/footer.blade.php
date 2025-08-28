<footer id="footer" class="dark" style="background-color: #1f2024;">
    <div class="container">
        <div class="footer-widgets-wrap row clearfix" style="padding-top: 30px;">
            <!-- Informasi Utama ============================================= -->
            @php
                $footers = $data['layout'];
                $social_medias = json_decode($footers['setting']['social_medias'])
            @endphp
            <div class="col-lg-5 col-sm-6 mb-5 mb-lg-0">
                <h4 class="m-0">{{ $footers['setting']['site_name'] }}</h4>
                <img style="max-width: 25%;" class="py-3" src="{{ asset('storage/'.$footers['setting']['logo']) }}" alt="">
                {{-- <div class="d-flex"> --}}
                    <address>
                        {{ $footers['setting']['address'] }}<br>
                        <abbr title="Fax"><strong>Fax:</strong></abbr> {{ $footers['setting']['fax'] }}<br>
                        <abbr title="Email Address"><strong>Email:</strong></abbr> {{ $footers['setting']['email'] }}
                    </address>
                {{-- </div> --}}
            </div>
            <!-- Tautan ============================================= -->
            <div class="col-lg-3 col-sm-5 mb-5 mb-sm-0">
                <h4 class="mb-3 mb-sm-4">TAUTAN</h4>
                <div class="widget widget_links clearfix">
                    <ul>
                        @foreach ($footers['links'] as $item)
                            <li><a href="{{ $item->text }}">{{ $item->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Social Media ============================================= -->
            <div class="col-lg-4 col-sm-6 mb-0">
                <h4 class="mb-3 mb-sm-4">SOSIAL MEDIA</h4>
                <div class="row center mt-4 clearfix">
                    @if (!empty($social_medias))
                    @foreach ($social_medias as $social)
                    <div class="col-3">
                        <a title="{{ $social->title }}" href="{{ $social->link }}" target="_blank" class="social-icon si-dark float-none m-auto si-colored">
                            <i class="{{ $social->icon }}"></i>
                            <i class="{{ $social->icon }}"></i>
                        </a>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Copyrights ============================================= -->
    <div id="copyrights">
        <div class="container clearfix">
            <div class="row justify-content-center">
                <span class="font-primary text-center"> Copyright &copy; <?= date("Y") ?> <a style="color: #27A719" href="https://diantara.co.id" target="_blank" rel="noopener noreferrer">PT DiAntara Inter Media.</a> All Rights Reserved.</span>
            </div>
        </div>
    </div>
</footer>
