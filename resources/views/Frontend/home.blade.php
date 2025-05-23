@extends('Frontend.layouts.default')


@push('custom_schema')
{{-- {!! SEOMeta::generate() !!} --}}
{{-- {!! JsonLd::generate() !!} --}}
{!! SEO::generate() !!}
@endpush


@section('content')
<div class="container py-2 d-flex flex-column justify-content-center text-start" 
     style="min-height: 50px; color: red;">
    <p class="mb-0" style="font-size: 14px;">
        Akaytruyen.com là web đọc truyện chính chủ duy nhất của tác giả AkayHau. <br>
        Tham gia kênh Youtube và Facebook Page chính của truyện để ủng hộ tác giả.
    </p>
</div>
    @include('Frontend.sections.main.stories_hot', ['categoryIdSelected' => 0])
<hr>
    <div>
        
        @include('Frontend.components.info_book')
        
    </div>
    

    <div class="container">
        <div class="row align-items-start">
            <div class="col-12 col-md-8 col-lg-9">
                @include('Frontend.sections.main.stories_new')
            </div>

            <div class="col-12 col-md-4 col-lg-3 sticky-md-top">
                <div class="row">
                    {{-- <div class="col-12 mb-3">
                        @include('Frontend.sections.main.stories_reading')
                    </div> --}}
                    <div class="col-12">
                        <br>
                        @include('Frontend.sections.main.list_category')
                    </div>
                    
                </div>
            </div>
            <br>
            @include('Frontend.sections.main.stories_full', ['stories' => $storiesFull])

        </div>


        
    </div>
    <div id="id_feedback_button">
        <a href="https://m.me/61573645333311" target="_blank" rel="noreferrer" class="btn">
            <svg viewBox="0 0 512 512" data-icon="messender" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M256,0C114.624,0,0,106.112,0,237.024c0,74.592,37.216,141.12,95.392,184.576V512l87.168-47.84c23.264,6.432,47.904,9.92,73.44,9.92c141.376,0,256-106.112,256-237.024C512,106.112,397.376,0,256,0z" style="fill: rgb(30, 136, 229);"></path><polygon points="281.44,319.2 216.256,249.664 89.056,319.2 228.96,170.656 295.744,240.192 421.376,170.656" style="fill: rgb(250, 250, 250);"></polygon></svg>
            <span class="ml-1">Liên Hệ QTV</span>
        </a>
    </div>
    <div class="container mt-4">
            <div class="section-list-category bg-light p-2 rounded card-custom w-100" style="max-width: 1300px;">
                <div id="chat-box">
                <h3 class="text-decoration-none text-dark">Luận Thiên Hạ</h3>
                <!-- <small>Đang phát triển</small> -->

                    <livewire:comment-section />





                       </div>

        </div>
    </div>
    <style>
        @media (max-width: 768px) { 
    .section-list-category {
        padding: 1rem; /* Giảm padding trên điện thoại */
    }
}

@media (max-width: 576px) {
    .section-list-category {
        padding: 0.5rem; /* Nhỏ hơn trên mobile */
    }
}
#id_feedback_button {
    position: fixed;
    right: -50px;
    top: 84%;
    transform: translateY(-50%);
}
#id_feedback_button .btn {
    color: #333;
    background-color: #ddd;
    transform: rotate(-90deg);
    float: right;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    opacity: 0.8;
}
.dark-theme .card-custom span, .dark-theme .card-custom a, .dark-theme .card-custom p {
    color: #2b0e0e !important;
}

body {
    font-family: 'Lora', serif;
}

.transition-transform {
    transition: transform 0.3s ease;
}

[aria-expanded="true"] .transition-transform {
    transform: rotate(180deg);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-menu-item {
    display: flex;
    align-items: center;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.mobile-menu-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.divider {
    opacity: 0.1;
}

.mobile-section {
    padding: 0 10px;
}

.blink-animation {
    animation: blink 1s infinite;
    top: -17px;
    right: -20px;
}

@keyframes blink {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.4;
    }

    100% {
        opacity: 1;
    }
}

.transition-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    padding: 0 0;
    transition: all 0.3s ease;
    background-color: transparent;
}

.transition-header.scrolled {
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 0 0;
    animation: slideDown 0.5s ease-in-out;
}

.transition-header.scrolled .nav-link {
    color: #333 !important;
}

.transition-header .navbar-toggler {
    color: #fff;
}

.transition-header.scrolled .navbar-toggler {
    color: #333;
}

.scrolled {
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 0 0;
    animation: slideDown 0.5s ease-in-out;
}

@keyframes slideDown {
    0% {
        transform: translateY(-100%);
    }

    100% {
        transform: translateY(0);
    }
}

.space-margins {
    margin-top: 200px;
}

/* custom */
.box-shadow-top {
    box-shadow: 0 -4px 8px 0 rgba(0, 0, 0, 0.1);
}

.box-shadow {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.underline-none {
    text-decoration: none;
}

.underline-none.hover:hover {
    text-decoration: underline;
}

.btn-rd-05rem {
    border-radius: .5rem;
}

.bg-coins-refund {
    background: linear-gradient(90deg, rgba(238, 9, 121, 1) 12%, rgba(255, 106, 0, 1) 83%);
}

.color-coins-refund {
    background: linear-gradient(90deg, rgba(238, 9, 121, 1) 12%, rgba(255, 106, 0, 1) 83%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.border-coins-refund {

    border: 1px solid;
    border-image: linear-gradient(90deg, rgba(238, 9, 121, 1) 12%, rgba(255, 106, 0, 1) 83%);
    border-image-slice: 1;
}

.border-coins-refund-2 {
    position: relative;
    border-radius: 12px;
    z-index: 1;
}

.border-coins-refund-2::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 12px;
    padding: 1.5px;
    background: linear-gradient(90deg, rgba(238, 9, 121, 1) 12%, rgba(255, 106, 0, 1) 83%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    z-index: -1;
}

.bg-e2e8f0 {
    background: #e2e8f0;
}

.bg-shopee {
    background: linear-gradient(90deg, rgba(238, 131, 9, 1) 12%, rgba(232, 208, 70, 1) 83%);
}

.line-height-16 {
    line-height: 16px;
}

.break-word {
    word-wrap: break-word;
}

.white-space-no-wrap-none {
    white-space: normal !important;
}

.responsive-text-xs {
    font-size: 0.5rem;
}

.responsive-text-small {
    font-size: 0.75rem;
}

.responsive-text-medium {
    font-size: 1rem;
}

.responsive-text-large {
    font-size: 1.25rem;
}

@media (min-width: 768px) {

    .responsive-text-xs {
        font-size: 0.75rem;
    }

    .responsive-text-small {
        font-size: 1rem;
    }

    .responsive-text-medium {
        font-size: 1.25rem;
    }

    .responsive-text-large {
        font-size: 1.5rem;
    }
}

@media (min-width: 992px) {
    .responsive-text-small {
        font-size: 1.25rem;
    }

    .responsive-text-small.none-lg {
        font-size: 1rem !important;
    }

    .lh-lg-none {
        line-height: normal !important;
    }

    .responsive-text-medium {
        font-size: 1.5rem;
    }

    .responsive-text-large {
        font-size: 1.75rem;
    }
}

.small-input {
    font-size: 0.75rem;
}

.small-input::placeholder {
    font-size: 0.75rem;
}


.medium-input {
    font-size: 1rem;
}

.medium-input::placeholder {
    font-size: 1rem;
}

.border-cl-shopee {
    border: 1px solid #ee4d2d;
}

.icon-menu {
    height: 35px;
    width: 35px;
}

.fs-7 {
    font-size: 0.875rem;
}

/* loading */
.loading-spinner {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* otp */

.otp-container {
    gap: 10px;
}

.otp-input {
    width: 50px;
    height: 50px;
    text-align: center;
    font-size: 24px;
    border: 2px solid #ccc;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.otp-input:focus {
    border-color: #007bff;
    outline: none;
}

/* Responsive cho màn hình nhỏ */
@media (max-width: 600px) {
    .otp-container {
        gap: 5px;
    }

    .otp-input {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
}

@media (max-width: 400px) {
    .otp-container {
        gap: 3px;
    }

    .otp-input {
        width: 35px;
        height: 35px;
        font-size: 18px;
    }
}


/* footer */

.chapter-list {
    list-style-type: none;
    padding: 0;
}

.chapter-list li a {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.chapter-list .date {
    font-weight: bold;
}

.chapter-list .date {
    font-weight: bold;
    border: 1px solid;
    display: flex;
    border-radius: 4px;
    text-align: center;
    width: 45px;
    /* Chiều rộng cố định */
    height: 45px;
    /* Chiều cao cố định để tạo hình vuông */

    line-height: 1.2;
    flex-direction: column;
    justify-content: center;
}

.chapter-list .date span {
    display: block;
    /* Hiển thị ngày và tháng theo hàng dọc */
}

.fs-7 {
    font-size: 0.7em;
}

.fs-8 {
    font-size: 0.8em;
}

.mt-80 {
    margin-top: 80px;
}

.mt-50 {
    margin-top: 50px;
}

.mt-120 {
    margin-top: 120px;
}

.color-a30000 {
    color: #a30000 !important;
}

.custom-container {
    width: 100%;
}

@media (min-width: 768px) {
    .custom-container {
        width: 768px !important;
    }
}

@media (min-width: 1024px) {
    .custom-container {
        width: 1024px !important;
    }
}


/* card info */
.info-card {
    padding: 1rem;
    background: #e7e7e7;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    text-align: center;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
}

.info-title {
    margin-bottom: 1rem;
    font-weight: 600;
}

.stats-list {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.counter {
    font-weight: bold;
    margin-right: 5px;
    transition: all 0.3s ease-out;
}

.stat-item {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* rating */
.rating {
    text-align: center;
}

.stars {
    display: inline-flex;
    gap: 5px;
}

.stars i {
    font-size: 24px;
    color: transparent;
    -webkit-text-stroke: 1px #ffd700;
}

.stars i.full {
    background: #ffd700;
    -webkit-background-clip: text;
    background-clip: text;
}

.stars i.half {
    background: linear-gradient(to right, #ffd700 50%, transparent 50%);
    -webkit-background-clip: text;
    background-clip: text;
}

.stars i.empty {
    background: transparent;
    -webkit-background-clip: text;
    background-clip: text;
}

.rating-number {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.info-card-home {
    background: #e7e7e7;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    text-align: center;
    transition: transform 0.3s ease;
}

.comment-list {
    max-height: 700px; /* Giới hạn chiều cao của danh sách bình luận */
    overflow-y: auto; /* Thêm thanh cuộn khi nội dung vượt quá */
    padding-right: 10px; /* Tạo khoảng trống bên phải để tránh che nội dung */
}

/* Tuỳ chỉnh thanh cuộn */
.comment-list::-webkit-scrollbar {
    width: 8px;
}

.comment-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.comment-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.comment-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}



    </style>


<hr>


        
    @endsection
