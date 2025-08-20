<section id="info-book ">
    <div class="container">
        @php
            $rating = auth()->check() ? auth()->user()->rating ?? 0 : 0;
            $fullStars = floor($rating);
            $hasHalfStar = $rating - $fullStars >= 0.5;
        @endphp
        <div class="row g-2 mt-2">
            <!-- Cột chứa THỐNG KÊ và ĐÁNH GIÁ -->
            <div class="col-12 col-lg-6 d-flex gap-3 flex-column" style="margin-top: 4.5rem !important;">
                <div class="info-card h-30">
                    <h6 class="info-title text-dark">THỐNG KÊ</h6>
                    <div class="stats-list">
                        <div class="stat-item text-dark">
                            <i class="fas fa-bookmark text-danger"></i>
                            <span class="counter" data-target="">{{ $totalStory }}</span>
                            <span>Truyện</span>
                        </div>
                        <div class="stat-item text-dark">
                            <i class="fas fa-bookmark text-danger"></i>
                            <span class="counter" data-target="">{{ $totalChapter }}</span>
                            <span>Chương</span>
                        </div>
                        <div class="stat-item text-dark">
                            <i class="fas fa-eye text-success"></i>
                            <span class="counter" data-target="">{{ $totalViews }}</span>
                            <span>Lượt Xem</span>
                        </div>
                        <div class="stat-item text-dark">
                            <i class="fas fa-star text-warning"></i>
                            <span class="counter" data-target="">{{ $totalRating }}</span>
                            <span>({{ number_format($rating, 1) }}/5) Đánh giá</span>
                        </div>
                    </div>
                </div>

                <div class="info-card mt-2">
                    <h6 class="info-title text-dark">ĐÁNH GIÁ</h6>
                    <div class="rating">
                        <div class="stars" id="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $fullStars ? 'full' : 'empty' }}"
                                    data-rating="{{ $i }}"></i>
                            @endfor
                        </div>
                        <div class="rating-number mt-2">{{ number_format($rating, 1) }}/5</div>
                    </div>
                </div>
            </div>

            <!-- Cột chứa TOP Donate (Chiều cao bằng với THỐNG KÊ + ĐÁNH GIÁ) -->
            <div class="col-lg-6">
                <div class="donate-container h-100 d-flex flex-column p-3 position-relative">
                    <h2 class="text-black text-center border-bottom pb-2"><b>Minh Chủ Bảng</b></h2>
                    <!-- Viền -->
                    <img src="{{ asset('assets/frontend/images/nenlogo.png') }}" class="border-img" alt="">

                    <div id="top-donate-list" class="position-relative">
                        @if ($topDonors->isEmpty())
                            <p class="donate-message">Chưa có ai donate tháng này.</p>
                        @else
                            <div class="table-responsive scrollable-table">
                                <table class="table table-bordered text-center align-middle donate-table">
                                    <thead>
                                        <tr>
                                            <th>Hạng</th>
                                            <th>Danh Tính</th>
                                            <th>Ủng Hộ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topDonors as $index => $donor)
                                            <tr>
                                                <td>
                                                    @if ($index == 0)
                                                        🏆
                                                    @elseif($index == 1)
                                                        🥈
                                                    @elseif($index == 2)
                                                        🥉
                                                    @else
                                                        <strong>{{ $index + 1 }}</strong>
                                                    @endif
                                                </td>
                                                <td>{{ $donor->name }}</td>
                                                <td class="text-success fw-bold">
                                                    {{ number_format($donor->donate_amount, 0) }} VND</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="text-center mb-3">
                        <div class="position-relative d-inline-block">
                            <!-- Nút mũi tên trái -->
                            <button id="btnPrevMonth"
                                class="btn btn-sm btn-light position-absolute start-0 top-50 translate-middle-y d-none">
                                ◀
                            </button>

                            <!-- Danh sách tháng có thể cuộn -->
                            <div id="monthScrollContainer" class="btn-group overflow-auto d-flex"
                                style="max-width: 334px; white-space: nowrap; scroll-behavior: smooth;">
                                @foreach ($months as $month)
                                    <a href="{{ route('home', ['month' => $month->month, 'year' => $month->year]) }}"
                                        class="btn btn-outline-greeen month-item {{ $month->month == $selectedMonth && $month->year == $selectedYear ? 'active' : '' }}">
                                        {{ $month->month }}/{{ substr($month->year, 2, 2) }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Nút mũi tên phải -->
                            <button id="btnNextMonth"
                                class="btn btn-sm btn-light position-absolute end-0 top-50 translate-middle-y d-none">
                                ▶
                            </button>
                        </div>
                    </div>



                </div>

            </div>

</section>



@push('styles')
    <style>
        .donate-table thead {
            position: sticky;
            top: 0;
            background: rgb(1, 112, 57);
            /* Màu nền để nổi bật */
            color: white;
            z-index: 2;
        }

        @font-face {
            font-family: 'FzSVGame';
            src: url('/fonts/FzSVGame.ttf') format('truetype'),
                url('/fonts/FzSVGame.ttf') format('woff'),
                url('/fonts/FzSVGame.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
        }

        .donate-container h2,
        .donate-table {
            font-smooth: always;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        .donate-container h2 {
            font-family: 'FzSVGame', sans-serif;
            font-size: 26px;
            font-weight: bold;
            color: #004085;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.3);
        }

        .donate-table {
            font-family: 'FzSVGame';
            font-size: 20px;
            color: #333;
        }

        .donate-table tbody tr:hover {
            background-color: #f0f8ff;
        }

        .scrollable-table {
            max-height: 263px;
            overflow-y: auto;
        }

        .scrollable-table::-webkit-scrollbar {
            width: 8px;
        }

        .scrollable-table::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .scrollable-table::-webkit-scrollbar-track {
            background-color: #f8f9fa;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            max-height: 400px;
        }

        .donate-table tbody tr:nth-child(odd) {
            background-color: #fffbe6;
        }

        .donate-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .border-img {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            max-width: 300px;
            height: auto;
            z-index: 1;
            pointer-events: none;
            image-rendering: crisp-edges;
            image-rendering: -webkit-optimize-contrast;
        }

        #top-donate-list {
            position: relative;
            z-index: 2;
            padding-top: 50px;
            top: -9%;
        }

        .text-center .btn-group {
            background-color: white;
            color: #333;
            top: -129%;
        }

        @media (max-width: 768px) {
            .donate-container h2 {
                font-size: 20px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            }

            .donate-table {
                font-size: 16px;
            }

            .scrollable-table {
                max-height: 200px;
            }

            .border-img {
                width: 90%;
                max-width: 250px;
            }

            #top-donate-list {
                padding-top: 30px;
                top: 0;
            }

            .text-center .btn-group {
                align-items: center;
                top: 50%;
                margin-top: 4%;
            }
        }

        .text-center .btn-group {
            top: 50%;
            border-radius: 0 !important;
        }


        #monthScrollContainer .month-item.active {
            background-color: green;
            color: black;
            border: 0px solid green;
        }




        @media (max-width: 480px) {
            .donate-container h2 {
                font-size: 18px;
            }

            .donate-table {
                font-size: 14px;
            }

            .border-img {
                max-width: 200px;
                top: 2%;
            }

            .scrollable-table {
                max-height: 316px;
            }
        }

        .donate-message {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            border: 2px dashed #fff;
            padding: 15px;
            border-radius: 10px;
            color: white;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Hover effect
            $('.stars i').hover(
                function() {
                    let rating = $(this).data('rating');
                    highlightStars(rating);
                },
                function() {
                    let currentRating = {{ $rating }};
                    highlightStars(currentRating);
                }
            );

            // Click handler
            $('.stars i').click(function() {
                @if (!auth()->check())
                    window.location.href = '{{ route('login') }}';
                    return;
                @endif

                let rating = $(this).data('rating');

                $.ajax({
                    url: '{{ route('ratings.store') }}',
                    type: 'POST',
                    data: {
                        rating: rating,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {

                        if (res.status === 'success') {
                            highlightStars(rating);
                            $('.rating-number').text(rating + '/5');
                            showToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);

                        showToast(xhr.responseJSON.message || 'Có lỗi xảy ra', 'error');
                    }
                });
            });

            function highlightStars(rating) {
                $('.stars i').each(function(index) {
                    if (index < rating) {
                        $(this).removeClass('empty').addClass('full');
                    } else {
                        $(this).removeClass('full').addClass('empty');
                    }
                });
            }
        });


        $(document).ready(function() {
            // Hover effect
            $('.stars i').hover(
                function() {
                    let rating = $(this).data('rating');
                    highlightStars(rating);
                },
                function() {
                    let currentRating = {{ $rating }};
                    highlightStars(currentRating);
                }
            );

            // Click handler
            $('.stars i').click(function() {
                @if (!auth()->check())
                    window.location.href = '{{ route('login') }}';
                    return;
                @endif

                let rating = $(this).data('rating');

                $.ajax({

                    type: 'POST',
                    data: {
                        rating: rating,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {

                        if (res.status === 'success') {
                            highlightStars(rating);
                            $('.rating-number').text(rating + '/5');
                            showToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);

                        showToast(xhr.responseJSON.message || 'Có lỗi xảy ra', 'error');
                    }
                });
            });

            function highlightStars(rating) {
                $('.stars i').each(function(index) {
                    if (index < rating) {
                        $(this).removeClass('empty').addClass('full');
                    } else {
                        $(this).removeClass('full').addClass('empty');
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("monthScrollContainer");
            const btnPrev = document.getElementById("btnPrevMonth");
            const btnNext = document.getElementById("btnNextMonth");
            const items = document.querySelectorAll(".month-item");
            const itemWidth = items.length > 0 ? items[0].offsetWidth : 0; // Lấy chiều rộng 1 item
            const visibleCount = 12; // Số lượng tháng hiển thị cùng lúc
            let scrollPosition = 0;

            function updateButtons() {
                btnPrev.style.display = scrollPosition > 0 ? "inline-block" : "none";
                btnNext.style.display = scrollPosition < (items.length - visibleCount) * itemWidth ?
                    "inline-block" : "none";
            }

            btnPrev.addEventListener("click", function() {
                scrollPosition = Math.max(scrollPosition - itemWidth * visibleCount, 0);
                container.scrollTo({
                    left: scrollPosition,
                    behavior: "smooth"
                });
                updateButtons();
            });

            btnNext.addEventListener("click", function() {
                scrollPosition = Math.min(scrollPosition + itemWidth * visibleCount, (items.length -
                    visibleCount) * itemWidth);
                container.scrollTo({
                    left: scrollPosition,
                    behavior: "smooth"
                });
                updateButtons();
            });

            updateButtons(); // Kiểm tra nút khi trang load
        });
    </script>
@endpush
