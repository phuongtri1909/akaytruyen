@extends('Admin.layouts.main')
@section('content')
<style>
    .home-button {
    text-decoration: none; /* Loại bỏ gạch chân của thẻ a */
}

.home-button button {
    background-color: #4CAF50; /* Màu nền xanh lá */
    color: white; /* Màu chữ trắng */
    padding: 15px 32px; /* Kích thước padding */
    text-align: center; /* Căn giữa chữ */
    text-decoration: none; /* Loại bỏ gạch chân */
    display: inline-block; /* Hiển thị dạng inline-block */
    font-size: 16px; /* Kích thước chữ */
    border: none; /* Loại bỏ viền */
    border-radius: 8px; /* Bo góc */
    cursor: pointer; /* Con trỏ chuột thành bàn tay */
    transition: background-color 0.3s ease; /* Hiệu ứng chuyển đổi màu nền */
}

.home-button button:hover {
    background-color: #45a049; /* Màu nền khi hover */
}

.home-button button:active {
    background-color: #3e8e41; /* Màu nền khi nhấn */
    transform: scale(0.98); /* Hiệu ứng nhấn nhẹ */
}
</style>
    <div class="row match-height">

        <div class="col-xl-4 col-lg-5 col-12">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-1">Chào mừng {{ Auth::user()->name }}! 🎉</h5>
                            <h4 class="text-primary mb-1">Đây là hệ thống quản lý truyện !</h4>
                            <a href="{{ route('home') }}" class="home-button">
                                <button>Về trang chính để đọc truyện nhé</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-12">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Thống kê</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        style="    width: 40px;
                                    fill: #917991;
                                    height: 40px;"
                                        viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z" />
                                    </svg>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalStory }}</h5>
                                    <small>Truyện</small>
                                </div>
                                <br>
                                
                            </div>

                        </div>



                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-1">
                                <i class="fas fa-eye text-success"></i>
                                </div>
                                <div class="card-info">
                                    <h5>{{ $totalViews }} </h5>
                                    <small>views</small>
                                    <h5>{{ $totalRating }}</h5>
                                    <small>Tổng lượt đánh giá</small>
                                </div>
                                
                            </div>

                        </div>
                                

                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg"  style="    width: 40px;
                                    fill: #917991;
                                    height: 40px;"
                                        viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                        <path
                                            d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5V78.6c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8V454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5V83.8c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11V456c0 11.4 11.7 19.3 22.4 15.5z" />
                                    </svg>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalChapter }}</h5>
                                    <small>Chương</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2 d-flex justify-content-between w-100 align-items-center">
                        <h5 class="m-0 me-2">Xếp hạng ngày</h5>
                        <a href="{{ route('admin.rating.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" style="fill: var(--color-primary);"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                            </svg>
                        </a>
                        {{-- <small class="text-muted">Total 10.4k Visitors</small> --}}
                    </div>
                </div>
                <div class="card-body">
                    @if ($ratingsDay && $storiesDay->count() > 0)
                        <ul class="p-0 m-0">
                            @foreach ($storiesDay as $k => $story)
                                <li class="d-flex mb-0 pb-1">
                                    <div class="me-3">
                                        <img src="{{ asset($story->image) }}" alt="User" class="rounded" width="46">
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $story->name }}</h6>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2 d-flex justify-content-between w-100 align-items-center">
                        <h5 class="m-0 me-2">Xếp hạng tháng</h5>
                        <a href="{{ route('admin.rating.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" style="fill: var(--color-primary);"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                            </svg>
                        </a>
                        {{-- <small class="text-muted">Total 10.4k Visitors</small> --}}
                    </div>
                </div>
                <div class="card-body">
                    @if ($ratingsMonth && $storiesMonth->count() > 0)
                        <ul class="p-0 m-0">
                            @foreach ($storiesMonth as $k => $story)
                                <li class="d-flex mb-0 pb-1">
                                    <div class="me-3">
                                        <img src="{{ asset($story->image) }}" alt="User" class="rounded" width="46">
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $story->name }}</h6>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2 d-flex justify-content-between w-100 align-items-center">
                        <h5 class="m-0 me-2">Xếp hạng all Time</h5>
                        <a href="{{ route('admin.rating.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" style="fill: var(--color-primary);"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                            </svg>
                        </a>
                        {{-- <small class="text-muted">Total 10.4k Visitors</small> --}}
                    </div>
                </div>
                <div class="card-body">
                    @if ($ratingsAllTime && $storiesAllTime->count() > 0)
                        <ul class="p-0 m-0">
                            @foreach ($storiesAllTime as $k => $story)
                                <li class="d-flex mb-0 pb-1">
                                    <div class="me-3">
                                        <img src="{{ asset($story->image) }}" alt="User" class="rounded"
                                            width="46">
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $story->name }}</h6>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2 d-flex justify-content-between w-100 align-items-center">            
                        <h5 class="m-0 me-2">Thống kê số lượt đang truy cập</h5>
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" style="fill: var(--color-primary);"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                            </svg>
                    </div>
                    <div class="card-body">
                        <!-- bất đâu thông kê -->
                        <canvas id="realtimeChart" width="100%" height="100"></canvas>
                            <p class="mt-3 mb-0">Đang người đang truy cập: <span id="online-users">0</span></p>
                            <p>Tổng requests: <span id="request-count">0</span></p>


                        <!-- kết thúc thống kê -->
                    </div>
                
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2 d-flex justify-content-between w-100 align-items-center">
                        <h5 class="m-0 me-2">Thông tin người dùng đang online</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Người dùng đã đăng nhập -->
                        <div class="col-md-6">
                            <h6 class="mb-2">
                                👤 Người dùng đã đăng nhập đang online
                                <span class="badge bg-primary" id="count-authenticated">0</span>
                            </h6>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tên người dùng</th>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="online-users-authenticated">
                                        <!-- Dữ liệu user đăng nhập -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Khách -->
                        <div class="col-md-6">
                            <h6 class="mb-2">
                                👥 Khách truy cập
                                <span class="badge bg-secondary" id="count-guests">0</span>
                            </h6>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>IP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="online-users-guests">
                                        <!-- Dữ liệu khách -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('realtimeChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Người đang truy cập', 'Requests'],
            datasets: [{
                data: [0, 0],
                backgroundColor: ['#36A2EB', '#FF6384']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    function updateChart() {
        $.ajax({
            url: '/admin/thong-ke-truy-cap',
            method: 'GET',
            success: function (data) {
                if (data.online !== undefined && data.requests !== undefined) {
                    chart.data.datasets[0].data = [data.online, data.requests];
                    chart.update();
                    $('#online-users').text(data.online);
                    $('#request-count').text(data.requests);
                } else {
                    console.error('Dữ liệu trả về không hợp lệ (chart)');
                }
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi lấy dữ liệu chart:', error);
            }
        });
    }

    function updateUserList() {
        $.ajax({
            url: '/admin/danh-sach-nguoi-dung-online',
            method: 'GET',
            success: function (data) {
                if (Array.isArray(data.users)) {
                    const authUsers = data.users.filter(u => u.name !== 'Khách');
                    const guests = data.users.filter(u => u.name === 'Khách');

                    $('#online-users-authenticated').empty();
                    $('#online-users-guests').empty();

                    // Hiển thị người dùng đăng nhập
                    authUsers.forEach(function (user) {
                        const row = $('<tr>');
                        row.append('<td>' + user.name + '</td>');
                        row.append('<td>' + user.ip + '</td>');
                        $('#online-users-authenticated').append(row);
                    });

                    // Hiển thị khách truy cập
                    guests.forEach(function (guest) {
                        const row = $('<tr>');
                        row.append('<td>' + guest.ip + '</td>');
                        $('#online-users-guests').append(row);
                    });

                    // Cập nhật số lượng
                    $('#count-authenticated').text(authUsers.length);
                    $('#count-guests').text(guests.length);
                } else {
                    console.error('Dữ liệu trả về không hợp lệ (user list)');
                }
            },
            error: function (xhr, status, error) {
                console.error('Lỗi khi lấy dữ liệu người dùng:', error);
            }
        });
    }



    // Gọi khi trang load và mỗi 5 giây
    updateChart();
    updateUserList();
    setInterval(updateChart, 5000);
    setInterval(updateUserList, 5000);
</script>
@endsection



