<div class="col-lg-3 sticky-sidebar-wrap mt-5 mt-lg-0">
    <div class="sticky-sidebar">
        @php $sidebar = $data['layout']['sidebar'] @endphp
        <div class="widget widget_links clearfix">
            <h3 class="m-0">Kategori Berita</h3>
            <div class="svg-line mb-2 clearfix">
                <img src="{{ asset('assets/front/demos/nonprofit/images/divider-1.svg') }}" alt="svg divider" height="10">
            </div>
            <ul>
                @foreach ($sidebar['news_category'] as $news_category)
                    <li class="d-flex align-items-center">
                        <a href="{{ route('news-category', $news_category->slug) }}" class="flex-fill">{{ $news_category->name }}</a>
                        <span class="badge text-light bg-sports">{{ $news_category->news_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="widget widget_links clearfix">
            <h3 class="m-0">Pengumuman</h3>
            <div class="svg-line mb-2 clearfix">
                <img src="{{ asset('assets/front/demos/nonprofit/images/divider-1.svg') }}" alt="svg divider" height="10">
            </div>
            <ul>
                @foreach ($sidebar['announcement'] as $announcement)
                    <li class="d-flex align-items-center">
                        <a href="{{ route('announcement.detail', $announcement->id) }}" class="flex-fill">{{ $announcement->title }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="widget widget_links clearfix">
            <h3 class="m-0">Jejak Pendapat</h3>
            <div class="svg-line mb-2 clearfix">
                <img src="{{ asset('assets/front/demos/nonprofit/images/divider-1.svg') }}" alt="svg divider" height="10">
            </div>
            <div class="col text-align-justify" id="vote-area">
                <form action="{{ route('save-vote') }}" class="form-inline" id="form-polling" method="post">
                    @csrf
                    @method('POST')
                    <p>{{ $sidebar['question']->nama }}</p>
                    <input type="hidden" name="question_id" value="{{ $sidebar['question']->id }}">
                    @foreach ($sidebar['answer'] as $answer)
                    <div class="form-check form-check">
                        <input class="form-check-input required valid answer_id" type="radio" name="answer_id" id="answer-name-individual-{{ $answer->id }}" value="{{ $answer->id }}">
                        <label class="form-check-label nott ms-2" for="answer-name-individual">{{ $answer->nama }}</label>
                    </div>
                    @endforeach
                    <button type="submit" class="button button-rounded ls0 ms-0 my-3">Kirim Jawaban</button>
                    <a href="#" onclick="holdClick(this)" id="lihat-jawaban" class="button button-rounded button-light ls0 ms-0 my-3">Lihat Jawaban</a>
                </form>
            </div>

        </div>

    </div>
</div>
<div style="display: none;">
    <div class="middle" style="margin-top: 70px;">
        <div class="bar bar1"></div>
        <div class="bar bar2"></div>
        <div class="bar bar3"></div>
        <div class="bar bar4"></div>
        <div class="bar bar5"></div>
        <div class="bar bar6"></div>
        <div class="bar bar7"></div>
        <div class="bar bar8"></div>
    </div>
    <div class="success-vote">
        <div class="alert alert-success" role="alert">
            Anda sudah menjawab polling kami !
        </div>
        <a href="#" id="lihat-jawaban" class="button button-rounded button-light ls0 ms-0 my-3">Lihat Jawaban</a>
    </div>
</div>


