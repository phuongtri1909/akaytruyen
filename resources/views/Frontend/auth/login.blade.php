@extends('Frontend.layouts.default')

@section('title', 'Đăng nhập')
@section('description', 'Đăng nhập AKAY TRUYỆN')
@section('keywords', 'Đăng nhập AKAY TRUYỆN')
@push('custom_schema')
{{-- {!! SEOMeta::generate() !!} --}}
{{-- {!! JsonLd::generate() !!} --}}
{!! SEO::generate() !!}
@endpush
@section('content')
<section class=" p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-xxl-11">
                    <div class="border-light shadow-sm rounded">
                        <div class="g-0">
                            <div class="col-12 d-flex align-items-center justify-content-center rounded">
                                <div class="col-12 col-lg-11 col-xl-10">
                                    <div class="card-body p-3 p-md-4 p-xl-5">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-5">
                                                    <div class="text-center mb-4">
                                                        <a href="{{ route('home') }}">
                                                            <img class="logo_conduongbachu"
                                                                src="{{ asset('assets/frontend/images/Logoakay.png') }}"
                                                                alt="logo_akay">
                                                        </a>
                                                    </div>
                                                    <h4 class="text-center color-coins-refund">Chào mừng bạn đã trở lại</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('user.login') }}" method="post">
                                            @csrf
                                            <div class="row gy-3 overflow-hidden">
                                                <div class="col-12">
                                                    <div class="form-floating mb-3">
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            name="email" id="email" placeholder="name@example.com"
                                                            value="{{ old('email') }}" required>
                                                        <label for="email" class="form-label">Nhập email của bạn</label>
                                                        @error('email')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-floating mb-3 position-relative">
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password" id="password" value=""
                                                            placeholder="Password" required>
                                                        <label for="password" class="form-label">Mật khẩu</label>
                                                        <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword"></i>

                                                        @error('password')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <label>
                                                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                                                </label>
                                                <div class="col-12">
                                                    <a href="{{ route('forgot-password') }}"
                                                        class="link-secondary text-decoration-none color-coins-refund">Quên
                                                        mật khẩu</a>
                                                </div>

                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-lg border-coins-refund-2 color-coins-refund btn-danger"
                                                            type="submit">Đăng nhập</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- <div class="text-center mt-3">
                                                    <a href="{{route('login.redirect','google')}}" class="btn btn-danger d-flex align-items-center justify-content-center">
                                                        <i class="fab fa-google me-2"></i> Đăng nhập bằng Google
                                                    </a>
                                                </div> -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div
                                                    class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-5">
                                                    <span>Bạn chưa có tài khoản? <a href="{{ route('register') }}"
                                                            class="link-secondary text-decoration-none color-coins-refund">Đăng
                                                            ký</a></span>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .logo_conduongbachu {
            height: 75px;
            object-fit: contain;
            transition: height 0.3s ease;
        }

        @media (max-width: 768px) {
            .logo_conduongbachu {
                height: 60px;
            }
        }

        @media (max-width: 576px) {
            .logo_conduongbachu {
                height: 50px;
            }
        }

    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @elseif (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
        });
    </script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>


@endpush

@push('styles')
    <style>
        .logo_conduongbachu {
    max-width: 100%; /* Logo không vượt quá khung chứa */
    height: auto; /* Giữ tỉ lệ ảnh */
    display: block; /* Tránh lỗi dư khoảng trắng */
    margin: 0 auto; /* Căn giữa logo */
}

/* Đặc biệt tối ưu cho điện thoại */
@media (max-width: 768px) {
    .logo_conduongbachu {
        max-width: 60%; /* Thu nhỏ logo trên màn hình nhỏ */
    }
}


    </style>
@endpush