@extends('Frontend.layouts.default')

@section('title', 'Đăng nhập')
@section('description', 'Đăng nhập AKAY TRUYỆN')
@section('keywords', 'Đăng nhập AKAY TRUYỆN')

@push('custom_schema')
{!! SEO::generate() !!}
@endpush

@section('content')
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
    .cursor-pointer {
        cursor: pointer;
    }
    .avatar-preview:hover {
        border-color:rgb(220, 230, 245) !important;
        opacity: 0.8;
    }
    .auth-container {
        max-width: 400px;
        margin: auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .form-floating input {
        border-radius: 8px;
    }

    .form-floating label {
        color: #6c757d;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }

    .google-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #dd4b39;
        color: #fff;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s ease;
    }

    .google-btn:hover {
        background-color: #c23321;
    }

    .google-btn i {
        font-size: 1.2rem;
    }
</style>

<section class="p-3 p-md-4 p-xl-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xxl-11">
                <div class="col-12">
                <div class="row">
                                            <div class="col-12">
                                                <div class="mb-5">
                                                    <div class="text-center mb-4">
                                                        <a href="{{ route('home') }}">
                                                            <img class="logo_conduongbachu"
                                                                src="{{ asset('assets/frontend/images/Logoakay.png') }}"
                                                                alt="logo_akay">
                                                        </a>
                                                    </div>                                                </div>
                                            </div>
                                        </div>

                                            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-floating mb-3">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="Email" required>
                                                    <label for="email">Email</label>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" placeholder="Họ và tên" required>
                                                    <label for="name">Họ và tên</label>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-floating mb-3 position-relative">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Mật khẩu" required>
                                                    <label for="password">Mật khẩu</label>
                                                    <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword"></i>
                                                    @error('password')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>


                                                <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                                            </form>
                                            <!-- <div class="text-center mt-3">
                                                <a href="{{route('login.redirect','google')}}" class="google-btn">
                                                    <i class="fab fa-google"></i> Đăng nhập với Google
                                                </a>
                                            </div> -->

                                            @if(session('success'))
                                                <div class="alert alert-success">{{ session('success') }}</div>
                                            @endif

                                            @if(session('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
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

<script>
    document.getElementById('avatarPreview').addEventListener('click', function () {
        document.getElementById('avatarInput').click();
    });

    document.getElementById('avatarInput').addEventListener('change', function (e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('avatarPreview').innerHTML = `<img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">`;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush
