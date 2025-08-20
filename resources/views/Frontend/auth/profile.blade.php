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
    @include('components.toast')
    <style>
        .avatar {
    width: 100px;
    height: 100px;
    background: #f1f1f1;
    display: flex
;
    justify-content: center;
    align-items: center;
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

    </style>
    <div class="custom-container container-fluid mt-80 mb-5">
        <section class="custom-container container-fluid mt-80 mb-5">
            <div class="p-3 bg-white box-shadow">
                <div class="d-flex">
                    <div class="rounded-circle border border-5 avatar border-secondary" id="avatar">

                    @if (!empty($user->avatar))
    <div style="position: relative; width: 100px; height: 100px;">
        <!-- Avatar -->
        <img id="avatarImage" class="rounded-circle"
            src="{{ asset($user->avatar) }}"
            alt="Avatar"
            style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">

        <!-- Viền theo vai trò -->
        @php
            $avatar = !empty($user->avatar) ? asset($user->avatar) : asset('assets/frontend/images/avatar_default.jpg');
            $role = $user->roles->first()->name ?? null;
            $email = $user->email ?? null;

            $borderMap = [
                'Admin' => 'admin-vip-8.png',
                'Mod' => 'avt_mod.png',
                'Content' => 'avt_content.png',
                'vip' => 'avt_admin.png',
                'VIP PRO' => 'avt_pro_vip.png',
                'VIP PRO MAX' => 'avt_vip_pro_max.gif',
                'VIP SIÊU VIỆT' => 'khung-sieu-viet.png',
            ];
            $border = null;
            $borderStyle = '';

            if ($role === 'Admin' && $email === 'nang2025@gmail.com') {
                $border = asset('images/roles/vien-thanh-nu.png');
            }
            elseif ($role === 'Admin' && $email === 'nguyenphuochau12t2@gmail.com') {
                $border = asset('images/roles/akay.png');
                $borderStyle = 'width: 280%; height: 280%; top: 31%;';
            } else {
                $border = isset($borderMap[$role]) ? asset('images/roles/' . $borderMap[$role]) : null;
            }
        @endphp


        @if ($border)
            <img src="{{ $border }}" alt="Border {{ $role }}"
                style="
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 180px;
                    height: 180px;
                    {{ $borderStyle ?: 'width: 180px; height: 180px;' }}
                    transform: translate(-50%, -50%);
                    pointer-events: none;
                    z-index: 1;
                    border-radius: 50%;
                ">
        @endif
    </div>
@else
    <i class="fa-solid fa-user" id="defaultIcon"></i>
@endif


                    </div>
                    <input type="file" id="avatarInput" style="display: none;" accept="image/*">
                    <div>
                        <h5 class="mt-3 ms-2">{{ $user->name }}</h5>
                        <span class="mt-3 ms-2">{{ $user->email }}</span><br>
                        <small><i>* click vào ô icon (ô tròn) để chọn ảnh || <b>chọn ảnh mới hiển thị viền của bạn</b></i></small>
                    </div>

                </div>
            </div>
        </section>

        <section class="mt-2 ">
            <div class="bg-white box-shadow ">
                <div class="p-3 d-flex justify-content-between border-bottom">
                    <span class="fw-semibold">ID</span>
                    <span>{{ $user->id }}</span>
                </div>
                <div class="p-3 d-flex justify-content-between border-bottom">
                    <span class="fw-semibold">role</span>
                    <span>{{ $user->roles->first()->name ?? 'No Role' }}</span>

                </div>
                <!-- Link chỉnh sửa -->
                <a href="#" class="underline-none text-dark p-3 d-flex justify-content-between border-bottom"
                    data-bs-toggle="modal" data-bs-target="#editModal" data-type="name">
                    <span class="fw-semibold">Họ và tên</span>
                    <div>
                        <span>{{ $user->name ? $user->name : '' }}</span>
                        <i class="fa-solid fa-chevron-right ms-2"></i>
                    </div>
                </a>

                <a href="#" class="underline-none text-dark p-3 d-flex justify-content-between border-bottom"
                    data-bs-toggle="modal" data-bs-target="#otpPWModal">
                    <span class="fw-semibold">Mật khẩu</span>
                    <div>
                        <span>******</span>
                        <i class="fa-solid fa-chevron-right ms-2"></i>
                    </div>
                </a>


                <!-- Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" action="{{ route('update.name.or.phone') }}" method="post">
                                    @csrf
                                    <div class="mb-3" id="formContent">
                                        <!-- Nội dung sẽ được cập nhật dựa trên loại dữ liệu được chọn -->
                                    </div>
                                    <div class="text-end">

                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-outline-success click-scroll" id="saveChanges">Lưu
                                            thay
                                            đổi</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="otpPWModal" tabindex="-1" aria-labelledby="otpPWModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="otpPWModalLabel">Xác thực OTP để đổi mật khẩu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="otpPWForm">
                                    @csrf
                                    <div class="mb-3 d-flex flex-column align-items-center" id="formOTPPWContent">
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="text-end box-button-update">

                                        <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-outline-success" id="btn-send-otpPW">Tiếp
                                            tục</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>

        <section class="mt-2 ">
            <div class="bg-white box-shadow ">
                <div class="p-3 border-bottom d-flex align-items-center">
                    <div class="rounded-circle bg-info icon-menu d-flex align-items-center justify-content-center"><i
                            class="fa-solid fa-arrow-right-from-bracket"></i></div>

                    <a href="{{ route('admin.logout') }}" class="fw-semibold ms-3 underline-none text-dark">Đăng xuất</a>
                </div>

            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Click vào avatar để mở file input
            $('#avatar').on('click', function() {
                $('#avatarInput').click();
            });

            // Xử lý khi người dùng chọn ảnh
            $('#avatarInput').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Hiển thị ảnh đã chọn
                        if (!$('#avatarImage').length) {
                            // Nếu chưa có ảnh (chỉ có icon), tạo thẻ <img> mới
                            $('#avatar').html('<img id="avatarImage" class="rounded-circle" src="' + e
                                .target.result +
                                '" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">'
                            );
                            $('#defaultIcon').hide();
                        } else {
                            // Nếu đã có ảnh, chỉ cần thay đổi src của ảnh
                            $('#avatarImage').attr('src', e.target.result).show();
                        }
                    };
                    reader.readAsDataURL(file);
                    var formData = new FormData();
                    formData.append('avatar', file);

                    $.ajax({
                        url: "{{ route('update.avatar') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showToast(response.message,
                                    'success');

                            } else {
                                showToast(response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            const response = xhr.responseJSON;
                            console.log('Error1:', response);
                        }
                    });
                }
            });
        });

        //update user info (name, phone)
        $(document).ready(function() {
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var type = button.data('type');
                var modal = $(this);

                var formContent = $('#formContent');
                formContent.empty();

                if (type == 'name') {
                    modal.find('.modal-title').text('Chỉnh sửa Họ và Tên');
                    formContent.append(`
                <label for="editValue" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="editValue" name="name" value="{{ $user->name }}" required>
            `);
                } else if (type == 'phone') {
                    modal.find('.modal-title').text('Chỉnh sửa Số điện thoại');
                    formContent.append(`
                <label for="editValue" class="form-label">Số điện thoại</label>
                <input type="number" class="form-control" id="editValue" name="phone" value="{{ $user->phone }}" required>
            `);
                } else {
                    showToast('Thao tác sai, hãy thử lại', 'error');
                }
            });
        });

        //update user password
        $(document).ready(function() {
            $('#otpPWModal').on('show.bs.modal', function(event) {
                var modal = $(this);
                $('#btn-send-otpPW').text('Tiếp tục');

                var formOTPContent = $('#formOTPPWContent');
                formOTPContent.empty();
                formOTPContent.append(`
                                <span class="text-center mb-1 title-otp-pw">
                                     <div class="spinner-border text-success" role="status">
                                         <span class="visually-hidden">Loading...</span>
                                     </div>
                                </span>
                                <div class="otp-container justify-content-center mb-3" id="input-otp-pw">
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <input type="text" maxlength="1" class="otp-input" oninput="handleInput(this)" />
                                    <br>
                                </div>
                            `);

                $.ajax({
                    url: "{{ route('update.password') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast(response.message, 'success');
                            $('.title-otp-pw').text(response.message).removeClass(
                                'text-danger').addClass('text-success');
                        } else {
                            $('.title-otp-pw').text(response.message).removeClass(
                                'text-success').addClass('text-danger');
                        }

                        //bước gửi otp lên server
                        $('#otpPWForm').on('submit', function(e) {
                            e.preventDefault();
                            var otp = '';
                            $('#otpPWForm .otp-input').each(function() {
                                otp += $(this).val();
                            });

                            $.ajax({
                                url: "{{ route('update.password') }}",
                                type: 'POST',
                                data: {
                                    otp: otp
                                },
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.status ==='success') {
                                        showToast(response.message,'success');

                                        formOTPContent.empty();
                                        formOTPContent.append(`
                                                    <span class="text-center mb-1 title-otp-pw">Hãy thay đổi mật khẩu mới!</span>

                                                    <div class="col-12">
                                                        <div class="form-floating mb-3 position-relative">
                                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" required>
                                                            <label for="password" class="form-label">Mật khẩu</label>
                                                            <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword"></i>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-floating mb-3 position-relative">
                                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" placeholder="Password" required>
                                                            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
                                                            <i class="fa fa-eye position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePasswordConfirm"></i>
                                                        </div>
                                                    </div>
                                                `);

                                        $('#btn-send-otpPW').text('Lưu thay đổi');

                                        $('#otpPWForm').off('submit').on('submit', function(e) {
                                            e.preventDefault();
                                            var formData = new FormData(this);
                                            formData.append('otp', otp);

                                            const passwordInput = $('#password');

                                            const oldInvalidFeedback = passwordInput.parent().find('.invalid-feedback');
                                                passwordInput.removeClass('is-invalid');
                                                    if (oldInvalidFeedback.length) {
                                                    oldInvalidFeedback.remove();
                                                }

                                            $.ajax({
                                                url: "{{ route('update.password') }}",
                                                type: 'POST',
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                success: function(response) {
                                                    if (response.status === 'success') {
                                                        showToast(response.message, 'success');
                                                        formOTPContent.empty();
                                                        $('#otpPWModal').modal('hide');
                                                    } else {
                                                        showToast(response.message, 'error');
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    const response = xhr.responseJSON;
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
                                        showToast(response.message,'error');
                                    }
                                },
                                error: function(xhr, status,error) {
                                    const input_otp = $('#input-otp-pw');
                                    input_otp.find('.invalid-otp').remove();
                                    const response = xhr.responseJSON;

                                    if (response && response.status ==='error') {
                                        if (response.message.otp) {
                                            input_otp.append(
                                                    `<div class="invalid-otp text-danger fs-7">${response.message.otp[0]}</div>`
                                                );
                                        }
                                    } else {
                                        showToast('Thao tác sai, hãy thử lại','error');
                                    }
                                }
                            });
                        });

                    },
                    error: function(xhr, status, error) {
                        const response = xhr.responseJSON;
                        console.log('Error1:', response);
                        showToast('Thao tác sai, hãy thử lại', 'error');
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

        //response save
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        @endif

        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                @if (is_array(session('error')))
                    @foreach (session('error') as $message)
                        @foreach ($message as $key => $value)
                            showToast('{{ $value }}', 'error');
                        @endforeach
                    @endforeach
                @else
                    showToast('{{ session('error') }}', 'error');
                @endif
            });
        @endif
    </script>
@endpush
@push('styles')
    <style>
        .avatar {
            width: 187px;
            height: 176px;
            background: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        img {
            image-rendering: auto;           /* Mặc định */
    /* Hoặc thử các giá trị sau nếu ảnh bị nhòe */
    image-rendering: crisp-edges;
    image-rendering: pixelated;
        }
    </style>
@endpush
