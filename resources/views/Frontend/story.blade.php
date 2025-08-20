@extends('Frontend.layouts.default')

@push('custom_schema')
    {{-- {!! SEOMeta::generate() !!} --}}
    {{-- {!! JsonLd::generate() !!} --}}
    {!! SEO::generate() !!}
@endpush

@push('before_content')
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v18.0&appId=642249148046913&autoLogAppEvents=1"
        nonce="Tt2viYEC"></script>
@endpush

@section('content')


    <style>
        .chapter-card {
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .chapter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stats-list-chapter {
            display: flex;
            flex-direction: row;
            gap: 0.8rem;
        }

        .counter-chapter {
            font-weight: bold;
            margin-right: 5px;
            transition: all 0.3s ease-out;
        }

        .stat-item-chapter {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .new-badge {
            color: #ff0000;
            font-weight: bold;
            margin-left: 5px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .new-badge {
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .chapter-list li a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .chapter-list .date {
            font-weight: bold;
            border: 1px solid;
            display: flex;
            border-radius: 4px;
            text-align: center;
            width: 45px;
            height: 51px;
            line-height: 1.2;
            flex-direction: column;
            justify-content: center;
        }

        .chapter-list {
            list-style: none;
            /* Xóa dấu chấm trước mỗi <li> */
            padding: 0;
            /* Loại bỏ khoảng trắng mặc định của danh sách */
        }

        .chapter-list li {
            border-bottom: none !important;
            /* Loại bỏ đường kẻ dưới mỗi mục */
            margin-bottom: 8px;
            /* Tạo khoảng cách giữa các chương */
        }
    </style>

    @php
        if (count($chapters->items()) > 1) {
            $arrChapters = array_chunk($chapters->items(), count($chapters->items()) / 2);
        } else {
            $arrChapters[] = $chapters->items();
        }
        $storyFinal = $story;
    @endphp

    <input type="hidden" id="story_slug" value="{{ $slug }}">
    <div class="container">
        <div class="row align-items-start">
            <div class="col-12 col-md-7 col-lg-8">
                <div class="head-title-global d-flex justify-content-between mb-4">
                    <div class="col-12 col-md-12 col-lg-4 head-title-global__left d-flex">
                        <h2 class="me-2 mb-0 border-bottom border-secondary pb-1">
                            <span class="d-block text-decoration-none text-dark fs-4 title-head-name"
                                title="Thông tin truyện">Thông
                                tin truyện</span>
                        </h2>
                    </div>
                </div>

                <div class="story-detail">
                    <div class="story-detail__top d-flex align-items-start">
                        <div class="row align-items-start">
                            <div class="col-12 col-md-12 col-lg-3 story-detail__top--image">
                                <div class="book-3d">
                                    <img src="{{ asset($story->image) }}" alt="{{ $story->name }}" class="img-fluid w-100"
                                        width="200" height="300" loading='lazy'>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-9">
                                <h3 class="text-center story-name">{{ $story->name }}</h3>
                                @if (isset($story->star))
                                    <div class="rate-story mb-2">
                                        <div class="rate-story__holder" data-score="{{ $story->star->stars }}">
                                            @php
                                                $valueStars = floatval($story->star->stars);
                                            @endphp

                                            @for ($i = 1; $i <= 10; $i++)
                                                @php
                                                    $tmp = $valueStars - $i;
                                                    $starImage = asset('/images/star-off.png');

                                                    if ($valueStars >= $i) {
                                                        if ($tmp > 0.5) {
                                                            $starImage = asset('/images/star-on.png');
                                                        } else {
                                                            $starImage = asset('/images/star-half.png');
                                                        }
                                                    } else {
                                                        // $tmp2 = $i - $valueStars;
                                                        // if ($tmp2 > 0 && $tmp2 < 0.5) {
                                                        //     $starImage = asset('/images/star-half.png');
                                                        // }
                                                    }
                                                @endphp

                                                <img alt="{{ $i }}" src="{{ $starImage }}" />

                                                {{-- @if ($i != 10)
                                                    &nbsp;
                                                @endif --}}
                                            @endfor

                                            {{-- <input name="score" type="hidden" value="6.3" readonly=""> --}}
                                        </div>
                                        <em class="rate-story__text"></em>
                                        <div class="rate-story__value" itemprop="aggregateRating" itemscope=""
                                            itemtype="https://schema.org/AggregateRating">
                                            <em>Đánh giá:
                                                <strong>
                                                    <span itemprop="ratingValue">{{ $story->star->stars }}</span>
                                                </strong>
                                                /
                                                <span class="" itemprop="bestRating">10</span>
                                                từ
                                                <strong>
                                                    <span itemprop="ratingCount">{{ $story->star->count }}</span>
                                                    lượt
                                                </strong>
                                            </em>
                                        </div>
                                    </div>
                                @endif

                                <div class="story-detail__top--desc px-3">
                                    {!! $story->desc !!}
                                </div>

                                <div class="info-more">
                                    <div class="info-more--more active" id="info_more">
                                        <span class="me-1 text-dark">Xem thêm</span>
                                        <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.70749 7.70718L13.7059 1.71002C14.336 1.08008 13.8899 0.00283241 12.9989 0.00283241L1.002 0.00283241C0.111048 0.00283241 -0.335095 1.08008 0.294974 1.71002L6.29343 7.70718C6.68394 8.09761 7.31699 8.09761 7.70749 7.70718Z"
                                                fill="#2C2C37"></path>
                                        </svg>
                                    </div>

                                    <a class="info-more--collapse text-decoration-none" href="#info_more">
                                        <span class="me-1 text-dark">Thu gọn</span>
                                        <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.70749 0.292817L13.7059 6.28998C14.336 6.91992 13.8899 7.99717 12.9989 7.99717L1.002 7.99717C0.111048 7.99717 -0.335095 6.91992 0.294974 6.28998L6.29343 0.292817C6.68394 -0.097606 7.31699 -0.0976055 7.70749 0.292817Z"
                                                fill="#2C2C37"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="story-detail__bottom mb-3">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-3 story-detail__bottom--info">
                                <p class="mb-1">
                                    <strong>Tác giả:</strong>
                                    <a href="#"
                                        class="text-decoration-none text-dark hover-title">{{ $story->author->name }}</a>
                                </p>
                                <div class="d-flex align-items-center mb-1 flex-wrap">
                                    <strong class="me-1">Thể loại:</strong>
                                    <div class="d-flex align-items-center flex-warp">
                                        @if ($story->categories)
                                            @foreach ($story->categories as $category)
                                                {{-- @if (!$loop->last)
                                                    <a href="#" class="text-decoration-none text-dark hover-title me-1" style="width: max-content;">{{ $category->name }},</a>
                                                @else
                                                    <a href="#"
                                                        class="text-decoration-none text-dark hover-title" style="width: max-content;">{{ $category->name }}</a>
                                                @endif --}}

                                                <a href="#"
                                                    class="text-decoration-none text-dark hover-title @if (!$loop->last) me-1 @endif"
                                                    style="width: max-content;">{{ $category->name }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{-- <p class="mb-1">
                                    <strong>Nguồn:</strong>
                                    <a href="#" class="text-decoration-none text-dark hover-title">Phong Nguyệt
                                        Lầu</a>
                                </p> --}}
                                <p class="mb-1">
                                    <strong>Trạng thái:</strong>
                                    <span class="text-info">{{ $story->is_full ? 'Full' : 'Đang ra' }}</span>
                                </p>
                            </div>

                            @if (count($chaptersNew) > 0)
                                <div class="col-12 col-md-12 col-lg-9">
                                    <div class="head-title-global d-flex justify-content-between mb-2">
                                        <div class="col-12 col-md-12 col-lg-6 head-title-global__left d-flex">
                                            <h2 class="me-2 mb-0 border-bottom border-secondary pb-1">

                                                <span href="#"
                                                    class="d-block text-decoration-none text-dark fs-4 title-head-name"
                                                    title="Truyện hot">Các chương mới nhất</span>
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="story-detail__bottom--chapters-new">
                                        <ul>
                                            @foreach ($chaptersNew as $chapterNew)
                                                <li style="list-style: none">
                                                    <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterNew->slug]) }}"
                                                        class="text-decoration-none text-dark hover-title"><span
                                                            class="new-badge">{{ $chapterNew->name }}</span></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- thong ke -->




                    <!-- end thong ke -->

                    <div class="story-detail__list-chapter">
                        <div class="container mt-2">
                            <div class="card-search py-2">
                                <div class="search-wrapper">
                                    <h5 class="text-center fw-bold">Tìm kiếm </h5>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search-chapter"
                                            data-story-slug="{{ $story->slug }}"
                                            placeholder="Chương, Tên chương, Nội dung ...">
                                        <button class="btn btn-primary" type="button" id="btn-search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="head-title-global d-flex justify-content-between mb-4">
                            <div class="col-6 col-md-12 col-lg-4 head-title-global__left d-flex">
                                <h2 class="me-2 mb-0 border-bottom border-secondary pb-1">
                                    <span href="#"
                                        class="d-block text-decoration-none text-dark fs-4 title-head-name"
                                        title="Truyện hot">Danh sách chương</span>
                                </h2>
                            </div>
                        </div>

                        <div class="form-check ms-0 ms-md-3 mt-3 mt-md-0">
                            <input class="form-check-input" type="checkbox" id="orderToggle"
                                {{ request()->input('old_first') ? 'checked' : '' }}>
                            <label class="form-check-label" for="orderToggle">
                                Chương cũ lên trước
                            </label>
                        </div>

                        @php
                            $isOldFirst = request()->input('old_first', false); // Lấy trạng thái từ URL
                            $reversedChapters = $isOldFirst
                                ? $chapters->sortBy('chapter')
                                : $chapters->sortByDesc('chapter');
                        @endphp



                        <div class="story-detail__list-chapter--list">

                            <div class="row" id="chapters-container">

                                {{-- Mobile View: Single Column --}}
                                <div class="d-block d-md-none">
                                    <ul class="chapter-list text-muted">
                                        @foreach ($reversedChapters as $chapter)
                                            <li class="mt-2">
                                                <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapter->slug]) }}"
                                                    class="text-muted">
                                                    <span class="date">
                                                        <span>{{ $chapter->created_at->format('d') }}</span>
                                                        <span
                                                            class="fs-7">{{ $chapter->created_at->format('m') }}</span>
                                                    </span>




                                                    <span class="chapter-title ms-2">
                                                        Chương {{ $chapter->chapter }}: {{ $chapter->name }}
                                                        @if ($chapter->created_at->isToday())
                                                            <span class="new-badge">New</span>
                                                        @endif
                                                    </span>
                                                </a>
                                                <hr class="my-2 opacity-25">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Desktop View: Two Columns --}}
                                <div class="col-6 d-none d-md-block">
                                    <ul class="chapter-list text-muted">
                                        @foreach ($reversedChapters->take(ceil($reversedChapters->count() / 2)) as $chapter)
                                            <li class="mt-2">
                                                <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapter->slug]) }}"
                                                    class="text-muted">
                                                    <span class="date">
                                                        <span>{{ $chapter->created_at->format('d') }}</span>
                                                        <span
                                                            class="fs-7">{{ $chapter->created_at->format('m') }}</span>
                                                    </span>

                                                    <span class="chapter-title ms-2">
                                                        Chương {{ $chapter->chapter }}: {{ $chapter->name }}
                                                        @if ($chapter->created_at->isToday())
                                                            <span class="new-badge">New</span>
                                                        @endif
                                                    </span>
                                                </a>
                                                <hr class="my-2 opacity-25">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="col-6 d-none d-md-block">
                                    <ul class="chapter-list text-muted">
                                        @foreach ($reversedChapters->skip(ceil($reversedChapters->count() / 2)) as $chapter)
                                            <li class="mt-2">
                                                <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapter->slug]) }}"
                                                    class="text-muted">
                                                    <span class="date">
                                                        <span>{{ $chapter->created_at->format('d') }}</span>
                                                        <span
                                                            class="fs-7">{{ $chapter->created_at->format('m') }}</span>
                                                    </span>

                                                    <span class="chapter-title ms-2">
                                                        Chương {{ $chapter->chapter }}: {{ $chapter->name }}
                                                        @if ($chapter->created_at->isToday())
                                                            <span class="new-badge">New</span>
                                                        @endif
                                                    </span>
                                                </a>
                                                <hr class="my-2 opacity-25">
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>



                    </div>



                    <div class="pagination" style="justify-content: center;">
                        {{ $chapters->appends(request()->query())->onEachSide(2)->links('Frontend.snippets.custom_pagination', ['has_ajax' => true]) }}
                    </div>

                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-4 sticky-md-top">
                @if ($story->author->stories->count() - 1 > 0)
                    <div class="section-stories-reading bg-light p-2 rounded mb-3 card-custom">
                        <div class="head-title-global mb-2">
                            <div class="col-12 col-md-12 head-title-global__left">
                                <h2 class="mb-0 border-bottom border-secondary pb-1">
                                    <span href="#" class="d-block text-decoration-none text-dark fs-4"
                                        title="Truyện đang đọc">Truyện cùng tác giả</span>
                                </h2>
                            </div>
                        </div>
                        <div class="stories-reading">
                            @foreach ($story->author->stories as $storyNear)
                                @if ($storyNear->slug != $story->slug)
                                    <div class="story-item-no-image">
                                        <div class="story-item-no-image__name d-flex align-items-center border-0"
                                            style="width: 70%;">
                                            <h3 class="me-1 mb-0 d-flex align-items-center">
                                                <i class="fa-solid fa-angle-right me-1"></i>
                                                <a href="{{ route('story', ['slug' => $storyNear->slug]) }}"
                                                    class="text-decoration-none text-dark fs-6 hover-title text-one-row">{{ $storyNear->name }}</a>
                                            </h3>
                                            @if ($storyNear->is_new)
                                                <span class="badge text-bg-info text-light me-1">New</span>
                                            @endif

                                            @if ($storyNear->is_full)
                                                <span class="badge text-bg-success text-light">Full</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @include('Frontend.snippets.top_ratings', [
                    'ratingsDay' => $ratingsDay,
                    'ratingsMonth' => $ratingsMonth,
                    'ratingsAllTime' => $ratingsAllTime,
                    'storiesDay' => $storiesDay,
                    'storiesMonth' => $storiesMonth,
                    'storiesAllTime' => $storiesAllTime,
                ])
                @include('Frontend.sections.main.list_category')
            </div>
        </div>

        {{-- Plugin FB Comment --}}
        {{-- <div class="fb-comments" data-href="https://developers.facebook.com/docs/plugins/comments#configurator"
            data-width="" data-numposts="5"></div> --}}
    </div>
@endsection

@push('scripts')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Book",
      "name": "{{ $storyFinal->name }}",
      "author": {
        "@type":"Person",
        "name": "{{ $storyFinal->author->name }}"
      },
      "url" : "{{ route('story', $storyFinal->slug) }}",
      "image": "{{ asset($storyFinal->image) }}",
      "thumbnailUrl": "{{ asset($storyFinal->image) }}",
      "description": "{!! Str::limit($storyFinal->desc, 50) !!}",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ isset($storyFinal->star) ? $storyFinal->star->stars : 0 }}",
        "bestRating": "10",
        "ratingCount": "{{ isset($storyFinal->star) ? $storyFinal->star->count : 0 }}"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Suu Truyen"
      }
    }
</script>
    <script type="application/ld+json">
        {
        "@context": "https://schema.org/",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Đọc Truyện",
                "item": "{{ route('home') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $storyFinal->name }}",
                "item": "{{ route('story', $storyFinal->slug) }}"
            }
        ]
        }
</script>

    <script src="{{ asset(mix('assets/frontend/js/story.js')) }}"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let searchTimeout;
            const searchInput = $('#search-chapter');
            const btnSearch = $('#btn-search');
            const chaptersContainer = $('#chapters-container');
            const loadMoreBtn = $('#load-more');
            const storySlug = $('#search-chapter').data('story-slug');

            function performSearch() {
                const searchTerm = searchInput.val();
                btnSearch.html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: `/truyen/${storySlug}/search-chapters`,
                    data: {
                        search: searchTerm,
                        page: 1
                    },
                    success: function(response) {
                        chaptersContainer.html(response.html);

                        if (response.hasMore) {
                            loadMoreBtn.data('page', 1).show()
                                .html(`Xem thêm 1/${response.lastPage}`);
                        } else {
                            loadMoreBtn.hide();
                        }
                    },
                    error: function() {
                        showToast('Có lỗi xảy ra khi tìm kiếm', 'error');
                    },
                    complete: function() {
                        btnSearch.html('<i class="fas fa-search"></i>');
                    }
                });
            }

            searchInput.on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });

            btnSearch.on('click', performSearch);
        });
        $('#close-search').click(function() {
            $('#search-results').addClass('d-none');
            $('#search-chapter').val('');
            removeHighlights();
        });

        // Click ra ngoài thì ẩn kết quả
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-wrapper').length) {
                $('#search-results').addClass('d-none');
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // function loadChapters() {
            //     const chapterRange = @json($ranges); // dùng json lấy giá trị từ controllerr trả ra
            //     const page = '{{ request('page') }} '; // lấy giá trị page từ req

            //     console.log(chapterRange);

            //     const start = chapterRange[0].start; //  lấy giá trị đầu tiên của mảng theo page
            //     const end = chapterRange[0].end; // lấy giá trị cuối của mảng theo page
            //     // let chapterRange = $('.chapter-list').val();

            //     // if (!chapterRange || !chapterRange.includes(',')) {
            //     //     console.log('Không tìm thấy giá trị chapter-list hoặc định dạng không hợp lệ');
            //     //     return;
            //     // }

            //     // const [start, end] = chapterRange.split(',');
            //     // console.log("end", end);
            //     // console.log("start", start);

            //     const isOldFirst = $('#orderToggle').prop('checked') ? 1 : 0;

            //     console.log('Gửi dữ liệu:', { start, end, old_first: isOldFirst });

            //     $.ajax({
            //         url: "{{ route('story', $story->slug) }}", // Laravel Blade syntax
            //         type: 'GET',
            //         data: {
            //             start: start,
            //             end: end,
            //             old_first: isOldFirst
            //         },
            //         success: function (response) {
            //             const tempDiv = document.createElement('div');
            //             tempDiv.innerHTML = response;

            //             // Xóa các thẻ <script> thừa
            //             const scripts = tempDiv.querySelectorAll('script');
            //             scripts.forEach(script => script.remove());

            //             document.getElementById('chapters-container').innerHTML = tempDiv.innerHTML;
            //         },
            //         error: function (xhr) {
            //             console.error('Có lỗi xảy ra khi tải dữ liệu', xhr.responseText);
            //         }
            //     });
            // }
            document.getElementById('orderToggle').addEventListener('change', function() {
                const isChecked = this.checked ? 1 : 0;
                const url = new URL(window.location.href);
                url.searchParams.set('old_first', isChecked);
                window.location.href = url.href;
            });

            // Gán sự kiện change cho danh sách chương
            // $('.chapter-list').on('change', loadChapters);

            // Gán sự kiện thay đổi thứ tự sắp xếp chương
            $('#orderToggle').on('change', function() {
                console.log('Checkbox changed:', $(this).prop('checked'));
                loadChapters();
            });
        });
    </script>
    </script>
@endpush
