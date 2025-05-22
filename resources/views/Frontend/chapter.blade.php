@extends('Frontend.layouts.default')

@push('custom_schema')
{{-- {!! SEOMeta::generate() !!} --}}
{{-- {!! JsonLd::generate() !!} --}}
{!! SEO::generate() !!}
@endpush

@section('content')

    <style>
        @media (max-width: 768px) { 
            .my-5 {
                margin-top: 1rem !important;
                margin-bottom: 2rem !important; /* Giảm khoảng cách trên mobile */
            }
        }
        @media (max-width: 576px) { 
            .my-5 {
                margin-top: 1.5rem !important;
                margin-bottom: 1.5rem !important;
            }
        }


        .chapter-start {
            background: url(//static.8cache.com/img/spriteimg_new_white_op.png) -200px -27px;
            width: 59px;
            height: 20px;
            border-top: none;
        }

        .chapter-end {
            background: url(//static.8cache.com/img/spriteimg_new_white_op.png) 0 -51px;
            width: 277px;
            height: 35px;
            border-top: none;
            position: relative; 
            top: -35px;

        }
        #chapter {
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .chapter-content {
            position: relative; 
            padding: 20px;
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            border-radius: 8px;
            scroll-behavior: smooth;
            /* Smooth scrolling */
        }
        @media (max-width: 768px) {
            .chapter-content {
                padding: 10px; /* Giảm padding để chữ không quá dính vào viền */
                margin: 5px;   /* Thêm khoảng cách nhỏ để không sát viền màn hình */
                font-size: 16px; /* Giảm font-size một chút để đọc dễ hơn */
            }
        }
        /* Customize scrollbar */
        .chapter-content::-webkit-scrollbar {
            width: 8px;
        }

        .chapter-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .chapter-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .chapter-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Themes */
        .theme-light {
            background-color: #fff;
            color: #333;
        }

        .theme-sepia {
            background-color: #f4ecd8;
            color: #5b4636;
        }

        .theme-dark {
            background-color: #2d2d2d;
            color: #ccc;
        }

        /* search  */
        #search-results {
            z-index: 1000;
        }

        #search-results .card {
            border: 1px solid rgba(0, 0, 0, .125);
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        #search-results .list-group-item {
            padding: 0.5rem 1rem;
            border-left: 0;
            border-right: 0;
        }

        #search-results .list-group-item:first-child {
            border-top: 0;
        }

        #search-results .list-group-item:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            #search-results {
                position: fixed !important;
                top: 60px;
                left: 0;
                right: 0;
                margin: 0 15px;
            }
        }
        /* Themes */
        .theme-light {
            background-color: #fff;
            color: #333;
        }

        .theme-sepia {
            background-color: #f4ecd8;
            color: #5b4636;
        }

        .theme-dark {
            background-color: #2d2d2d;
            color: #ccc;

            
        }
        .chapter-content {

    font-family: var(--font-choice, 'Noto Sans', sans-serif);
}
.highlight {
    background-color: yellow;
    color: black;
    font-weight: bold;
    padding: 2px 4px;
    border-radius: 4px;
}
#chapter {
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .chapter-content {

            padding: 20px;
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            border-radius: 8px;
            scroll-behavior: smooth;
            /* Smooth scrolling */
        }
        .custom-text {
    font-size: 24px;
    position: relative;
    left: -1px;
    top: -21px;
}

/* Điều chỉnh trên điện thoại */
@media (max-width: 768px) {
    .custom-text {
        font-size: 18px; /* Giảm kích thước chữ */
        top: -15px; /* Điều chỉnh vị trí */
    }
}

@media (max-width: 480px) {
    .custom-text {
        font-size: 24px; /* Giảm nữa trên màn hình nhỏ hơn */
        top: -10px; /* Điều chỉnh vị trí phù hợp */
    }
}

    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Noto+Serif&family=Charter&display=swap" rel="stylesheet">

    <div class="chapter-wrapper container my-5">
        
        <!-- <hr class="chapter-start container-fluid"> -->

        <div class="chapter-nav text-center main">
                            {{-- Tiêu đề chương + dropdown chọn chương --}}
                            <div class="text-center">
                    <!-- <h1 class="chapter-title h4 mb-0 text-center mb-3">
                        Chương {{ $chapter->chapter }}: {{ $chapter->name }}
                    </h1> -->

                    
                                     
                </div>
            <div class="chapter-nav d-flex justify-content-between align-items-center mb-4 top-0">
                {{-- Nút chương trước --}}
                @if ($chapterBefore)
                    <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterBefore->slug]) }}" 
                        class="btn btn-success text-white px-3">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @else
                    <button disabled class="btn btn-outline-secondary px-3">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @endif

                <strong class="chapter-nav text-center">
                    <a href="{{ route('story', ['slug' => $chapter->story->slug]) }}" 
                    style="color: red; position: relative; left: -4px; top: 1px; text-decoration: none;">
                        <i>{{ $chapter->story->name }}</i>
                    </a>
                </strong>


                {{-- Nút chương tiếp theo --}}
                @if ($chapterAfter)
                    <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterAfter->slug]) }}" 
                        class="btn btn-success text-white px-3">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <button disabled class="btn btn-outline-secondary px-3">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                @endif
            </div>
            <h1 class="text-center text-success custom-text"><b>Chương {{ $chapter->chapter }}: {{ $chapter->name }}</b>  </h1>
        </div>

                        <!-- <div class="save-chapter-control text-center mt-3">
                        @if(auth()->check() && optional(auth()->user()->savedChapters)->contains($chapter->id))
                                <button class="btn btn-danger" id="remove-chapter">
                                    <i class="fas fa-times"></i> <span id="remove-text">Hủy lưu chương</span>
                                </button>
                            @else
                                <button class="btn btn-warning" id="save-chapter">
                                    <i class="fas fa-bookmark"></i> <span id="save-text">Lưu chương</span>
                                </button>
                            @endif
                        </div> -->
                        <div class="container mt-2">
                            <div class="card-search py-2 text-center">
                                <div>
                                    <style>
                                        .fs-8 {
                                            font-size: 0.8em;
                                        }
                                        .custom-element {
    position: relative;
    top: -59px;
}
.input-group{
    position: relative;
    top: -21px;

}
.main {
    position: relative;
    top: -71px;
}

.timkiem {
    position: relative;
    top: -28px;
}

/* Điều chỉnh trên tablet (màn hình <= 768px) */
@media (max-width: 768px) {
    .main {
        top: -50px; /* Giảm khoảng cách */
    }

    .timkiem {
        top: -15px; /* Điều chỉnh nhẹ */
    }

    .custom-element {
        position: relative;
        top: -20px; /* Giảm khoảng cách */
        left: 1px;
    }
}

/* Điều chỉnh trên điện thoại nhỏ (màn hình <= 480px) */
@media (max-width: 480px) {
    .main {
        top: -30px; /* Giảm khoảng cách để không bị lệch quá */
    }

    .timkiem {
        top: -10px; /* Căn chỉnh lại */
    }

    .custom-element {
        position: relative;
        top: -50px; /* Điều chỉnh xuống */
        left: 0;
    }
}


                                    </style>
                                    <span class="fs-8 custom-element">
                                        <i class="fa-regular fa-file-word"></i> Tiểu thuyết gốc: {{ $chapter->word_count }} Chữ 
                                        <span class="ms-2">
                                            <i class="fa-regular fa-clock"></i> {{ $chapter->created_at }}
                                        </span>
                                    </span>
                                </div>

                                
                                <div class="search-wrapper position-relative timkiem">
                                    <div class="search-wrapper">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="search"
                                                placeholder="Tìm Nội dung ...">
                                            <button class="btn btn-primary" type="button" id="btn-search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <!-- <div id="search-results" class="position-absolute w-100 mt-1 d-none">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center py-2">
                                                    <span>Kết quả tìm kiếm</span>
                                                    <button type="button" class="btn-close" id="close-search"></button>
                                                </div>
                                                <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                                                    <div class="list-group list-group-flush" id="results-list"></div>
                                                </div>
                                            </div>
                                        </div> -->
                                    
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr class="chapter-end container-fluid">

                        
                            <!-- <div class="reading-controls bg-light p-3 mb-4 rounded">

                                <div class="save-chapter-control text-center mt-3">
                                    @if(auth()->check() && auth()->user()->savedChapters->contains($chapter->id))
                                        <button class="btn btn-danger" id="remove-chapter">
                                            <i class="fas fa-times"></i> <span id="remove-text">Hủy lưu chương</span>
                                        </button>
                                    @else
                                        <button class="btn btn-warning" id="save-chapter">
                                            <i class="fas fa-bookmark"></i> <span id="save-text">Lưu chương</span>
                                        </button>
                                    @endif
                                </div>


                            </div> -->
        

        {{-- @dd(config('fonts.roboto'), $chapterFont) --}}
        <div class=""> {{-- chapter-content mb-3 --}}
        <style>
            .chapter-content {
                @if ($chapterFont)
                    font-family: {!! config('fonts.'.$chapterFont) !!}
                @endif
                @if ($chapterFontSize)
                    font-size: {{ $chapterFontSize }}px;
                @endif
                @if ($chapterLineHeight)
                    line-height: {{ $chapterLineHeight }}%;
                @endif
            }
            .custom-alert {
        background-color: #28a745; /* Xanh lá cây đậm */
        color: white;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .custom-alert a {
        color: black;
        text-decoration: underline;
        font-weight: bold;
    }

    .custom-alert a:hover {
        color:rgb(0, 0, 0);
    }
    @media (max-width: 768px) {
    #chapter-content {
        padding: 0px !important;
        margin: 0px !important;
    }
}

        </style>
        <div>
            <!-- @guest
                <div class="custom-alert">
                    📖 Bạn cần <a href="{{ route('login') }}">đăng nhập</a> hoặc <a href="{{ route('register') }}">đăng ký</a> để đọc chương này.
                </div>
            @endguest -->
        </div>
        <?php
    // Giải mã các ký tự HTML
    $chapter->content = html_entity_decode(htmlspecialchars_decode($chapter->content), ENT_QUOTES, 'UTF-8');

    // Thay thế các ký tự đặc biệt của Word thành ký tự chuẩn
    $word_special_chars = [
        '&ldquo;' => '“', '&rdquo;' => '”', '&lsquo;' => '‘', '&rsquo;' => '’',
        '&nbsp;' => ' ', '&hellip;' => '...', '&ndash;' => '-', '&mdash;' => '—',
        '‐' => '-', '‑' => '-', '‒' => '-', '–' => '-', '—' => '-'
    ];
    $chapter->content = str_replace(array_keys($word_special_chars), array_values($word_special_chars), $chapter->content);
    $chapter->content = str_replace(['&nbsp;', "\xc2\xa0"], ' ', $chapter->content);

    // Giữ nguyên xuống dòng từ Word thành các đoạn văn <p>
    $chapter->content = preg_replace('/\r\n|\r|\n/', "</p><p>", $chapter->content);

    // **🔥 Xóa khoảng trắng trước và sau dấu `. " ' ” ’` (cả khi có dấu câu trước)**
    $chapter->content = preg_replace('/\s*([.,!?]?\s*[."\'”’])\s*/u', '$1', $chapter->content);

    // **🔥 Đảm bảo có khoảng trắng sau dấu câu (, . ! ?) nếu không có khoảng trắng**
    $chapter->content = preg_replace('/([.,!?])([^\s”’])/u', '$1 $2', $chapter->content);

    // Đảm bảo nội dung luôn có thẻ <p>
    $chapter->content = "<p>" . $chapter->content . "</p>";

    // Xóa các thẻ <p> rỗng
    $chapter->content = preg_replace('/<p>\s*<\/p>/', '', $chapter->content);
?>



@php
    $restrictedSlug = 'con-duong-ba-chu-ngoai-truyen';
    $allowedRoles = ['Admin', 'vip', 'Mod', 'SEO', 'Content', 'VIP PRO', 'VIP PRO MAX', 'VIP SIÊU VIỆT'];
@endphp

<div id="chapter-content" class="chapter-content mb-4 p-3 border-0 rounded" 
    style="font-size: 1.5rem; min-height: 500px; line-height: 2; position: relative; top: -54px;">
    @if($slugStory === $restrictedSlug)
        @if(auth()->check() && auth()->user()->hasAnyRole($allowedRoles))
            <!-- Nếu người dùng có quyền, hiển thị nội dung chapter -->
            {!! $chapter->content !!}
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Chặn chuột phải
                    document.addEventListener('contextmenu', function(event) {
                        event.preventDefault();
                    });

                    // Chặn tổ hợp phím sao chép (Ctrl + C, Ctrl + X, Ctrl + U, Ctrl + A)
                    document.addEventListener('keydown', function(event) {
                        if ((event.ctrlKey && ['c', 'x', 'u', 'a'].includes(event.key.toLowerCase())) || event.key === 'F12') {
                            event.preventDefault();
                        }
                    });

                    // Chặn chọn văn bản
                    document.getElementById('chapter-content').style.userSelect = 'none';
                });
            </script>
        @else
            <!-- Nếu không có quyền, hiển thị thông báo nâng cấp -->
            <div class="alert alert-success text-center" style="border: 2px dashed #28a745;">
                <strong>Bạn vui lòng nâng cấp lên VIP ở đây hoặc trên kênh Youtube để đọc toàn bộ truyện này.</strong>
            </div>
        @endif
    @else
        <!-- Nếu không phải chapter bị hạn chế, hiển thị nội dung bình thường -->
        {!! $chapter->content !!}
    @endif
</div>








                
                    <div class="chapter-nav d-flex justify-content-between align-items-center mb-4">
                        {{-- Nút chương trước --}}
                        @if ($chapterBefore)
                            <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterBefore->slug]) }}" 
                                class="btn btn-success text-white px-3">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @else
                            <button disabled class="btn btn-outline-secondary px-3">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        @endif

                        {{-- Nút download EPUB (chỉ hiển thị nếu có quyền) --}}
                        @auth
                            @if($story->slug !== 'con-duong-ba-chu-ngoai-truyen') <!-- Kiểm tra slugStory để chặn tải cho truyện này -->
                                @if(auth()->user()->hasRole('Admin'))
                                    <a href="{{ route('download.epub', ['slugStory' => $story->slug, 'slugChapter' => $chapter->slug]) }}" 
                                        class="btn btn-success px-4">
                                        <i class="fas fa-download"></i> Download EPUB
                                    </a>
                                @endif
                            @else
                                <!-- Trường hợp truyện là "con-duong-ba-chu-ngoai-truyen", không hiển thị nút tải -->
                                <div class="alert alert-success text-center">
                                    <strong>Truyện này không cho phép tải về.</strong>
                                </div>
                            @endif
                        @endauth

                        
                        {{-- Nút chương tiếp theo --}}
                        @if ($chapterAfter)
                            <a href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterAfter->slug]) }}" 
                                class="btn btn-success text-white px-3">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <button disabled class="btn btn-outline-secondary px-3">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let chapterContent = document.getElementById("chapter-content");
                        if (chapterContent) {
                            chapterContent.innerHTML = chapterContent.innerHTML.replace(/([^\s])\./g, "$1. ");
                        }
                    });

                </script>

        </div>
        
            <div class="section-list-category bg-light p-2 rounded w-100">
                    @if (!Auth()->check() || (Auth()->check() && Auth()->user()->ban_comment == false))
                        @include('Frontend.components.comment', ['pinnedComments' => $pinnedComments, 'regularComments' => $regularComments])
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-sad-tear fa-4x text-muted mb-3 animate__animated animate__shakeX"></i>
                            <h5 class="text-danger">Bạn đã bị cấm bình luận!</h5>
                        </div>
                    @endif
            </div>
        </div>






    <div class="chapter-actions chapter-actions-mobile d-flex align-items-center justify-content-center">
        @if ($chapterBefore)
        <a class="btn btn-success me-2 chapter-prev" href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterBefore->slug]) }}" title=""> <span>Chương </span>trước</a>
        @endif
        <button class="btn btn-success chapter_jump me-2"  data-story-id="{{ $story->id }}" data-slug-chapter="{{ $slugChapter }}" data-slug-story="{{ $story->slug }}"><span>
                {{-- <i class="fa-solid fa-bars"></i> --}}
                <svg style="fill: #fff;" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
            </span></button>

        <div class="dropup select-chapter me-2 d-none">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdown@iMenuLink"
                data-bs-toggle="dropdown" aria-expanded="false">
                Chọn chương
            </a>

            <ul class="dropdown-menu select-chapter__list" aria-labelledby="dropdownMenuLink">
                {{-- @foreach ($story->chapters as $chapterItem)
                    <li>
                        <a class="dropdown-item @if($slugChapter == 'chuong-'.$chapterItem->chapter ) bg-info text-light @endif" data-chapter-position="chuong-{{ $chapterItem->chapter }}"
                            href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterItem->slug]) }}">Chương
                            {{ $chapterItem->chapter }}</a>
                    </li>
                @endforeach --}}
            </ul>
        </div>
        @if ($chapterAfter)
        <a class="btn btn-success chapter-next" href="{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => $chapterAfter->slug]) }}" title=""><span>Chương </span>tiếp</a>
        @endif
    </div>
    
@endsection

@push('scripts')
<script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Đọc Truyện",
        "item": "{{ route('home') }}"
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "{{ $story->name }}",
        "item": "{{ route('story', $story->slug) }}"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $chapter->name }}",
        "item": "{{ route('chapter', ['slugStory' => $story->slug, 'slugChapter' => 'chuong-'.($chapter->chapter)]) }}"
      }]
    }
    </script>
    <script src="{{ asset(mix('assets/frontend/js/chapter.js')) }}"></script>
@endpush

@once
    @push('scripts')
        <script>

window.objConfigFont = [
    { name: 'roboto', value: "'Roboto Condensed', sans-serif" },
    { name: 'mooli', value: "'Mooli', sans-serif" },
    { name: 'patrick_hand', value: "'Patrick Hand', cursive" },
    { name: 'noto_sans', value: "'Noto Sans', sans-serif" },
    { name: 'noto_serif', value: "'Noto Serif', serif" },
    { name: 'charter', value: "'Charter', serif" }
];

// Xử lý thay đổi font chữ
$(document).ready(function() {
    let $fontSelect = $('.setting-font');

    // Thêm tùy chọn vào dropdown
    window.objConfigFont.forEach(font => {
        $fontSelect.append(`<option value="${font.name}">${font.name.replace('_', ' ').toUpperCase()}</option>`);
    });

    // Cập nhật font khi người dùng chọn
    $fontSelect.change(function() {
        let selectedFont = $(this).val();
        let fontObj = window.objConfigFont.find(f => f.name === selectedFont);
        if (fontObj) {
            $('.chapter-content').css('font-family', fontObj.value);
            localStorage.setItem('chapterFont', selectedFont);
        }
    });

    // Áp dụng font lưu trong localStorage
    let savedFont = localStorage.getItem('chapterFont');
    if (savedFont) {
        let fontObj = window.objConfigFont.find(f => f.name === savedFont);
        if (fontObj) {
            $('.chapter-content').css('font-family', fontObj.value);
            $fontSelect.val(savedFont);
        }
    }
});

let searchTimeout;
let currentIndex = -1;
let matches = [];

// Xử lý tìm kiếm khi nhập vào input
$('#search').on('input', function() {
    clearTimeout(searchTimeout);
    const searchTerm = $(this).val().trim().toLowerCase();

    if (searchTerm.length < 2) {
        removeHighlights();
        return;
    }

    searchTimeout = setTimeout(() => {
        highlightText(searchTerm);
    }, 300);
});

// Xử lý khi đóng tìm kiếm
$('#close-search').click(function() {
    $('#search').val('');
    removeHighlights();
});

// Click ra ngoài thì ẩn kết quả
$(document).on('click', function(e) {
    if (!$(e.target).closest('.search-wrapper').length) {
        removeHighlights();
    }
});

// Hàm highlight từ khóa tìm kiếm trong nội dung chương
function highlightText(searchTerm) {
    removeHighlights(); // Xóa highlight cũ

    const regex = new RegExp(searchTerm, 'gi');
    matches = [];

    $('.chapter-content').each(function() {
        const $this = $(this);
        const html = $this.html();
        const newHtml = html.replace(regex, function(matched) {
            matches.push(matched);
            return `<span class="highlight">${matched}</span>`;
        });
        $this.html(newHtml);
    });

    if (matches.length > 0) {
        currentIndex = 0;
        scrollToMatch(currentIndex);
    }
}

// Xóa tất cả highlight
function removeHighlights() {
    $('.highlight').each(function() {
        $(this).replaceWith($(this).text());
    });
    matches = [];
    currentIndex = -1;
}

// Cuộn đến kết quả đang chọn
function scrollToMatch(index) {
    if (matches.length === 0) return;

    const element = $('.highlight').eq(index);
    if (element.length) {
        $('html, body').animate({
            scrollTop: element.offset().top - 100
        }, 300);
    }
}

// Xử lý phím Enter để điều hướng giữa các kết quả tìm kiếm
$(document).keydown(function(e) {
    if (matches.length === 0) return;

    if (e.key === 'Enter') {
        if (e.shiftKey) {
            // Shift + Enter: Chuyển về kết quả trước
            currentIndex = (currentIndex - 1 + matches.length) % matches.length;
        } else {
            // Enter: Chuyển đến kết quả tiếp theo
            currentIndex = (currentIndex + 1) % matches.length;
        }
        scrollToMatch(currentIndex);
    }
});


    
</script>

<script>
        const content = document.getElementById('chapter-content');
        const chapterSection = document.getElementById('chapter');
        let fontSize = localStorage.getItem('fontSize') || 18;
        let theme = localStorage.getItem('theme') || 'light';

        // Font size controls
        function changeFontSize(delta) {
            fontSize = Math.max(14, Math.min(24, parseInt(fontSize) + delta));
            content.style.fontSize = `${fontSize}px`;
            localStorage.setItem('fontSize', fontSize);
        }
        // Initialize settings
        window.addEventListener('DOMContentLoaded', () => {
            content.style.fontSize = `${fontSize}px`;
            // applyTheme(theme);
        });

        document.addEventListener("DOMContentLoaded", function () {
    let chapterContent = document.getElementById("chapter-content");
    if (chapterContent) {
        let content = chapterContent.innerHTML;

        // 🔥 Xóa khoảng trắng trước dấu câu (. ! ? ,) và trước dấu ngoặc kép (" ’ ”)
        content = content.replace(/\s*([.,!?])\s*(["'”’])/g, '$1$2');

        // 🔥 Đảm bảo có khoảng trắng sau dấu câu nếu cần
        content = content.replace(/([.,!?])([^\s"”’])/g, '$1 $2');

        // 🔥 Giữ khoảng cách sau dấu ngoặc kép nếu cần
        content = content.replace(/(["'”’])([^\s.,!?])/g, '$1 $2');

        // Cập nhật lại nội dung
        chapterContent.innerHTML = content;
    }
});

    </script>



<style>
    /* CSS để tô màu các kết quả tìm kiếm */
    .highlight-search {
        background-color: yellow;
        color: black;
        font-weight: bold;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>

    @endpush
@endonce
