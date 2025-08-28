<header id="header" class="header-size-sm border-bottom-0" data-sticky-shrink="false">
    <div id="header-wrap">
        <div class="container">
            <div class="header-row justify-content-lg-between">

                @php $header = $data['layout']; @endphp
                <div id="logo" class="me-lg-5">
                    <a href="{{ url('/') }}" class="standard-logo"><img class="p-2" src="{{ asset('storage/'.$header['setting']['logo']) }}"></a>
                    <a href="{{ url('/') }}" class="retina-logo"><img class="p-2" src="{{ asset('storage/'.$header['setting']['logo']) }}"></a>
                </div>

                <div id="primary-menu-trigger">
                    <svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
                </div>

                <nav class="primary-menu with-arrows me-lg-auto">
                    @php
                        $menus = $data['layout']['navbar'];
                    @endphp
                    <ul class="menu-container align-self-start">
                        <li class="menu-item"><span class="menu-bg col-auto align-self-start d-flex"></span></li>
                        @foreach ($menus['items'] as $item)
                            @if (count($item->child) <= 0)
                                <li class="menu-item"><a {{ $item->new_tab == 'on' ? 'target="_blank"' : '' }} class="menu-link" href="{{ $item->link }}"><div>{{ $item->label }}</div></a></li>
                            @else
                                {{-- <a href="http://" target="_blank" rel="noopener noreferrer"></a> --}}
                                <li class="menu-item mega-menu mega-menu-small">
                                    <a class="menu-link" onclick="holdClick(this)" href="#"><div>{{ $item->label }}</div></a>
                                    <div class="mega-menu-content border-top-0 mega-menu-style-2" style="width: 270px">
                                        <div class="container">
                                            <div class="row">
                                                <ul class="sub-menu-container mega-menu-column col-lg-12">
                                                    <li class="menu-item mega-menu-title">
                                                        <ul class="sub-menu-container">
                                                            @foreach ($item->child as $item)
                                                            @if (count($item->child) <= 0)
                                                            <li class="menu-item">
                                                                <a {{ $item->new_tab == 'on' ? 'target="_blank"' : '' }} class="menu-link" href="{{ $item->link }}"><div>{{ $item->label }}</div></a>
                                                            </li>
                                                            @else
                                                            <li class="menu-item">
                                                                <a class="menu-link" onclick="holdClick(this)" href="#"><div>{{ $item->label }}</div></a>
                                                                <ul class="sub-menu-container mega-menu-dropdown">
                                                                    @foreach ($item->child as $item)
                                                                    <li class="menu-item">
                                                                        <a {{ $item->new_tab == 'on' ? 'target="_blank"' : '' }} class="menu-link" href="{{ $item->link }}"><div>{{ $item->label }}</div></a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                            @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                </nav>

            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header>
<script>

    window.onload = function(){
        var link = document.getElementsByClassName('non-aktif-link');
        for (var i = 0; i < link.length; i++) {
            link[i].addEventListener('click', function(event){
                event.preventDefault();
            });
        }
    };

</script>
