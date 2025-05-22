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
        border-color: #0d6efd !important;
        opacity: 0.8;
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
</style>

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
                                                    <h4 class="text-center color-coins-refund">Bạn quên mật khẩu rồi à?</h4>
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <form id="forgotForm">
                                            <div class="row gy-3 gx-0 overflow-hidden">
                                                <div class="col-12 form-email">
                                                    <div class="form-floating mb-3">
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            name="email" id="email" placeholder="name@example.com"
                                                            value="{{ old('email') }}" required>
                                                        <label for="email" class="form-label">Nhập email của bạn</label>
                                                    </div>
                                                </div>

                                                <div id="otpContainer" class="overflow-hidden text-center">

                                                </div>

                                                <div id="passwordContainer" ></div>

                                                <div class="box-button col-12">
                                                    <button
                                                        class="w-100 btn btn-lg border-coins-refund-2 color-coins-refund"
                                                        type="submit" id="btn-send">Tiếp tục</button>
                                                </div>

                                            </div>
                                        </form>
                                        <div class="row">
                                            <div class="col-12">
                                                <div
                                                    class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-5">
                                                    <span>Bạn đã nhớ mật khẩu? <a href="{{ route('login') }}" class="link-secondary text-decoration-none color-coins-refund">Đăng nhập</a></span>
                                                    
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
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#forgotForm').on('submit', function(e) {
                e.preventDefault();
                const emailInput = $('#email');
                const email = emailInput.val();
                const submitButton = $('#btn-send');

                // Xóa thông báo lỗi cũ nếu tồn tại
                const oldInvalidFeedback = emailInput.parent().find('.invalid-feedback');
                emailInput.removeClass('is-invalid');
                if (oldInvalidFeedback.length) {
                    oldInvalidFeedback.remove();
                }

                // Thay đổi nút submit thành trạng thái loading
                submitButton.prop('disabled', true);
                submitButton.html('<span class="loading-spinner"></span> Đang xử lý...');

                $.ajax({
                    url: '{{ route('forgot.password') }}',
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({
                        email: email
                    }),
                    success: function(response) {

                        if (response.status === 'success') {
                            showToast(response.message, 'success');
                            submitButton.remove();

                            $('.form-email').remove();

                            $('#otpContainer').html(`
                        <span class="text-center mb-1">${response.message}</span>
                        <div class="otp-container justify-content-center mb-3" id="input-otp">
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                            <br>
                        </div>
                    `);

                            $('.box-button').html(`
                        <button class="w-100 btn btn-lg border-coins-refund-2 color-coins-refund" type="button" id="submitOtp">Tiếp tục</button>
                    `);

                            $('#submitOtp').on('click', function() {
                                const otpInputs = $('.otp-input');
                                const input_otp = $('#input-otp');

                                let otp = '';
                                otpInputs.each(function() {
                                    otp += $(this).val();
                                });

                                input_otp.find('.invalid-otp').remove();

                                const oldInvalidFeedbackEmail = emailInput.parent().find('.invalid-feedback');
                                emailInput.removeClass('is-invalid');
                                if (oldInvalidFeedbackEmail.length) {
                                    oldInvalidFeedbackEmail.remove();
                                }

                                $.ajax({
                                    url: '{{ route('forgot.password') }}',
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    data: JSON.stringify({
                                        email: email,
                                        otp: otp,
                                    }),
                                    success: function(response) {
                                        
                                        if (response.status === 'success') {
                                            showToast(response.message,
                                                'success');
                                                $('#submitOtp').remove();
                                                $('#otpContainer').remove();

                                                $('#passwordContainer').html(`
                                                <span class="text-center mb-1">${response.message}</span>
                                                <div class="col-12">
                                                    <div class="form-floating mb-3 position-relative">
                                                        <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                                        <label for="password" class="form-label">Mật khẩu</label>
                                                        <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword"></i>
                                                    </div>
                                                </div>
                                            `);

                                                    $('.box-button').html(`
                                                <button class="w-100 btn btn-lg border-coins-refund-2 color-coins-refund" type="button" id="submitPassword">Xác nhận</button>
                                            `);

                                            $('#submitPassword').on('click', function() {
                                                const passwordInput = $('#password');
                                                const password = passwordInput.val();

                                                const oldInvalidFeedback = passwordInput.parent().find('.invalid-feedback');
                                                passwordInput.removeClass('is-invalid');
                                                    if (oldInvalidFeedback.length) {
                                                    oldInvalidFeedback.remove();
                                                }

                                                $.ajax({
                                                    url: '{{ route('forgot.password') }}',
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    data: JSON.stringify({
                                                        email: email,
                                                        otp: otp,
                                                        password: password
                                                    }),
                                                    success: function(response) {
                                                        if (response.status === 'success') {
                                                            showToast(response.message,
                                                                'success');
                                                            saveToast(response.message,
                                                                response.status);
                                                            window.location.href = response
                                                                .url;
                                                        } else {
                                                            showToast(response.message, 'error');
                                                        }
                                                    },
                                                    error: function(xhr) {
                                                        const response = xhr.responseJSON;
                                                        console.log('Error2:', response);

                                                        if (response && response.status === 'error') {
                                                            if (response.message.password) {
                                                                response.message.password.forEach(error => {
                                                                    const invalidFeedback = $('<div class="invalid-feedback"></div>').text(error);
                                                                    passwordInput.addClass('is-invalid').parent().append(invalidFeedback);
                                                                });
                                                            }
                                                        } else {
                                                            showToast('Đã xảy ra lỗi, vui lòng thử lại.', 'error');
                                                        }
                                                    }
                                                });
                                            });


                                        } else {
                                            showToast(response.message,
                                                'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        const response = xhr.responseJSON;
                                        console.log('Error2:', response);

                                        if (response && response.status ===
                                            'error') {
                                            if (response.message.email) {
                                                response.message.email.forEach(error => {
                                                    const invalidFeedback = $('<div class="invalid-feedback"></div>').text(error);
                                                    emailInput.addClass('is-invalid').parent().append(invalidFeedback);
                                                });
                                            }
                                            if (response.message.otp) {
                                                input_otp.append(`<div class="invalid-otp text-danger fs-7">${response.message.otp[0]}</div>`);
                                            }
                                        } else {
                                            showToast(
                                                'Đã xảy ra lỗi, vui lòng thử lại.',
                                                'error');
                                        }
                                    }
                                });
                            });

                        } else {
                            showToast(response.message, 'error');
                            submitButton.prop('disabled', false);
                            submitButton.html('Tiếp tục');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
console.log('Error1:', response);

                        if (response && response.message && response.message.email) {
                            response.message.email.forEach(error => {
                                const invalidFeedback = $('<div class="invalid-feedback"></div>').text(error);
                                emailInput.addClass('is-invalid').parent().append(invalidFeedback);
                            });
                        } else {
                            showToast('Đã xảy ra lỗi, vui lòng thử lại.', 'error');
                        }
                        submitButton.prop('disabled', false);
                        submitButton.html('Tiếp tục');
                    }
                });
            });
        });

        //hidden password

$(document).on('click', '#togglePassword', function () {
    const passwordField = $('#password')
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password'
    passwordField.attr('type', type)

    $(this).toggleClass('fa-eye fa-eye-slash')
})

//hidden password confirm

$(document).on('click', '#togglePasswordConfirm', function () {
    const passwordField = $('#password_confirmation')
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password'
    passwordField.attr('type', type)

    $(this).toggleClass('fa-eye fa-eye-slash')
})

//toast
function showToast(message, status) {
    document.addEventListener('DOMContentLoaded', function() {
        const toastElement = document.getElementById('liveToast');
        if (!toastElement) return;

        const toastBody = toastElement.querySelector('.toast-body');
        if (!toastBody) return;

        // Update message
        toastBody.textContent = message;

        // Remove existing classes
        toastElement.classList.remove('bg-success', 'bg-danger', 'text-white');

        // Add new classes based on status
        if (status === 'success') {
            toastElement.classList.add('bg-success', 'text-white');
        } else if (status === 'error') {
            toastElement.classList.add('bg-danger', 'text-white');
        }

        // Initialize and show toast
        const bsToast = new bootstrap.Toast(toastElement);
        bsToast.show();
    });
}

//save toast

function saveToast (message, status) {
    sessionStorage.setItem('toastMessage', message)
    sessionStorage.setItem('toastStatus', status)
}

//show toast
function showSavedToast() {
    const message = sessionStorage.getItem('toastMessage');
    const status = sessionStorage.getItem('toastStatus');

    if (message && status) {
        showToast(message, status);
        sessionStorage.removeItem('toastMessage');
        sessionStorage.removeItem('toastStatus');
    }
}

//otp

function handleInput(element) {
    $(element).val(
        $(element).val().replace(/[^0-9]/g, '')
    );

    if ($(element).val().length === 1) {
        const nextInput = $(element).next('.otp-input');
        if (nextInput.length) {
            nextInput.focus();
        }
    }
}

$(document).on('keydown', '.otp-input', function(e) {
    if (e.key === 'Backspace' || e.key === 'Delete') {
        e.preventDefault();
        const $currentInput = $(this);
        const $prevInput = $currentInput.prev('.otp-input');
        
        if ($currentInput.val()) {
            // If current input has value, clear it
            $currentInput.val('');
        } else if ($prevInput.length) {
            // If current input is empty, move to previous input and clear it
            $prevInput.val('').focus();
        }
    }
});

$(document).on('input', '.otp-input', function() {
    const $this = $(this);
    const maxLength = parseInt($this.attr('maxlength'));
    
    if ($this.val().length > maxLength) {
        $this.val($this.val().slice(0, maxLength));
    }
    
    // Move to next input if value is entered
    if ($this.val().length === maxLength) {
        const $nextInput = $this.next('.otp-input');
        if ($nextInput.length) {
            $nextInput.focus();
        }
    }
});

    </script>

@endpush