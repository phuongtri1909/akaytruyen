@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/styles.css') }}" rel="stylesheet">
@endpush

@php
    $level = 0;
    $currentComment = $comment;
    // Count levels by checking parent comments
    if ($currentComment && $currentComment->reply_id) {
        while ($currentComment->reply_id && $currentComment->parent) {
            $level++;
            $currentComment = $currentComment->parent;
        }
    }
@endphp
<div class="comment-list">
    <li class="clearfix d-flex" id="comment-{{ $comment->id }} ">

        @php
            $avatar =
                $comment->user && $comment->user->avatar
                    ? asset($comment->user->avatar)
                    : asset('assets/frontend/images/avatar_default.jpg');

            $role = $comment->user ? $comment->user->getRoleNames()->first() : null;
            $email = $comment->user ? $comment->user->email : null;

            $borderMap = [
                'Admin' => 'admin-vip-8.png',
                'Mod' => 'vien_mod.png',
                'Content' => 'avt_content.png',
                'vip' => 'avt_admin.png',
                'VIP PRO' => 'ma-van-dang-tptk.gif',
                'VIP PRO MAX' => 'avt_vip_pro_max.gif',
                'VIP SIÊU VIỆT' => 'khung-sieu-viet.png',
            ];
            $border = null;
            $borderStyle = '';

            if ($role === 'Admin' && $email === 'nang2025@gmail.com') {
                $border = asset('images/roles/vien-thanh-nu.png');
            } elseif ($role === 'Admin' && $email === 'nguyenphuochau12t2@gmail.com') {
                $border = asset('images/roles/akay-vip-16.png');
                $borderStyle = 'width: 200%; height: 200%; top: 31%;';
            } else {
                $border = isset($borderMap[$role]) ? asset('images/roles/' . $borderMap[$role]) : null;
            }
        @endphp

        <div class="avatar-wrapper"
            style="position: relative; width: 60px; height: 60px; display: inline-block; flex-shrink: 0;">
            <!-- Ảnh Avatar -->
            <img src="{{ $avatar }}" class="rounded-circle border border-3"
                alt="{{ $comment->user ? $comment->user->name : 'Người dùng không tồn tại' }}"
                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">

            <!-- Ảnh viền nếu có -->
            @if ($border)
                <img src="{{ $border }}" class="rounded-circle" alt="Border {{ $role }}"
                    style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        {{ $borderStyle ?: 'width: 130%; height: 130%;' }}

                        transform: translate(-50%, -50%);
                        pointer-events: none;
                        z-index: 1;
                        border-radius: 50%;
                    ">
            @endif
        </div>




        <div class="post-comments p-2 p-md-3">
            <div class="content-post-comments">
                @php
                    $userRole =
                        $comment->user && $comment->user->roles ? $comment->user->roles->pluck('name')->first() : null;

                @endphp

                <p class="meta mb-2">
                    <a class="fw-bold ms-2 text-decoration-none" target="_blank">
                        @if ($comment->user)
                            @if ($userRole === 'Admin')
                                <span class="role-badge admin-badge">
                                    @if (auth()->check() && auth()->user()->hasRole('Admin'))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none admin-badge">
                                            [ADM] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [ADM] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'Mod')
                                <span class="role-badge mod-badge">
                                    @if (auth()->check() && auth()->user()->hasRole('Admin'))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none mod-badge">
                                            [MOD] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [MOD] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'vip')
                                <span class="role-badge vip-badge">
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none vip-badge">
                                            [VIP] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [VIP] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'Content')
                                <span class="role-badge content-badge">
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none content-badge">
                                            [CONTENT] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [CONTENT] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'VIP PRO')
                                <span class="role-badge vip-pro-badge">
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none vip-pro-badge">
                                            [VIP PRO] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [VIP PRO] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'VIP PRO MAX')
                                <span class="role-badge vip-pro-max-badge">
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none vip-pro-max-badge">
                                            [VIP PRO MAX] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [VIP PRO MAX] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @elseif ($userRole === 'VIP SIÊU VIỆT')
                                <span class="role-badge vip-pro-sv-badge">
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none vip-pro-sv-badge">
                                            [VIP SIÊU VIỆT] <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        [VIP SIÊU VIỆT] <b>{{ $comment->user->name }}</b>
                                    @endif
                                </span>
                            @else
                                @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                    <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                        class="text-decoration-none text-dark">
                                        <b>{{ $comment->user->name }}</b>
                                    </a>
                                @else
                                    <span class="text-dark"><b>{{ $comment->user->name }}</b></span>
                                @endif


                            @endif
                            {{-- Badge theo email --}}
                            @if ($comment->user && $comment->user->email === 'khaicybers@gmail.com')
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/64012-management.png" width="30px"
                                        style="margin-left:5px; margin-top:-10px;" alt="Hỗ trợ">
                                    <span class="tooltip-text">Hỗ Trợ</span>
                                </span>
                            @elseif($comment->user && $comment->user->email === 'nguyenphuochau12t2@gmail.com')
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/65928-owner.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="tac gia">
                                    <span class="tooltip-text">Tác Giả</span>
                                </span>
                            @elseif($comment->user && $comment->user->hasRole('Admin'))
                                {{-- Nếu là Admin nhưng không nằm trong danh sách email đặc biệt --}}
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/39760-owner.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="Admin">
                                    <span class="tooltip-text">Quản Trị Viên</span>
                                </span>
                            @endif

                            {{-- Badge theo vai trò --}}
                            @if ($comment->user && $comment->user->hasRole('Mod'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/80156-developer.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="Mod">
                                    <span class="tooltip-text">Mod kiểm duyệt</span>
                                </span>
                            @elseif($comment->user && $comment->user->hasRole('vip'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/45918-msp-super-vip.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="vip1">
                                    <span class="tooltip-text">Tinh Anh Bậc I</span>
                                </span>
                            @elseif($comment->user && $comment->user->hasRole('VIP PRO'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/44014-msp-elite-vip.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="vip2">
                                    <span class="tooltip-text">Hộ Pháp Bậc II</span>
                                </span>
                            @elseif($comment->user && $comment->user->hasRole('VIP PRO MAX'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/55280-msp-star-vip.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="vip3">
                                    <span class="tooltip-text">Trưởng Lão Bậc III</span>
                                </span>
                            @elseif($comment->user && $comment->user->hasRole('VIP SIÊU VIỆT'))
                                <span class="tooltip-icon">
                                    <img src="https://cdn3.emoji.gg/emojis/2336-vipgif.gif" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="vipmax">
                                    <img src="https://cdn3.emoji.gg/emojis/53879-bluevip.png" width="30px"
                                        style="margin-left:5px;margin-top:-10px;" alt="vipmax">
                                    <span class="tooltip-text">Thái Thượng</span>
                                </span>
                            @endif
                        @else
                            <span>Người dùng không tồn tại</span>
                        @endif
                    </a>


                    {{-- Nút xóa comment nếu user có quyền --}}
                    @if ($comment->level == 0 && auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                        <span class="delete-comment text-danger ms-2" style="cursor: pointer;"
                            data-id="{{ $comment->id }}">
                            <i class="fas fa-times"></i>
                        </span>
                    @endif

                    {{-- Nút ghim comment chỉ Admin có quyền --}}
                    @if ($comment->level == 0 && auth()->check() && auth()->user()->hasRole('Admin'))
                        <button class="btn btn-sm pin-comment ms-2" data-id="{{ $comment->id }}">
                            @if ($comment->is_pinned)
                                <i class="fas fa-thumbtack text-warning" title="Bỏ ghim"></i>
                            @else
                                <i class="fas fa-thumbtack" title="Ghim"></i>
                            @endif
                        </button>
                    @endif

                </p>

                <span class="mb-2" id="comment-{{ $comment->id }}">
                    @if ($comment->user && $comment->user->hasRole('VIP SIÊU VIỆT'))
                        <span class="vip-super-role">{!! nl2br(e($comment->comment)) !!}</span>
                    @else
                        {!! nl2br(e($comment->comment)) !!}
                    @endif
                </span>


                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 flex-sm-nowrap w-100">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-muted small comment-time">
                            {{ $comment->created_at->locale('vi')->diffForHumans() }}
                        </span>

                        @if ($comment->level < 1 && auth()->check())
                            <small class="reply-btn text-decoration-underline" style="cursor: pointer;"
                                data-id="{{ $comment->id }}">
                                Trả lời <i class="fa-solid fa-share ms-1"></i>
                            </small>
                        @endif
                    </div>


                    <!-- Nút mở các reaction và biểu tượng mặt cười -->
                    <div class="reaction-wrapper position-relative d-flex align-items-center">
                        <!-- Nút mặt cười -->
                        <button class="btn btn-sm btn-outline-secondary smiley-btn" style="margin-right: 10px;">
                            <i class="fa-regular fa-face-smile"></i>
                        </button>

                        <!-- Nhóm nút reactions -->
                        <div class="reaction-group d-flex gap-1 p-1 bg-white border rounded shadow-sm"
                            style="
                                position: absolute;
                                bottom: 98%;
                                left: -175px;
                                display: none;
                                z-index: 999;
                            ">
                            <button class="btn btn-sm btn-outline-primary reaction-btn" data-type="like"
                                data-id="{{ $comment->id }}">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary reaction-btn" data-type="dislike"
                                data-id="{{ $comment->id }}">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning reaction-btn" data-type="haha"
                                data-id="{{ $comment->id }}">
                                <i class="fa-solid fa-face-laugh-squint"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger reaction-btn" data-type="tym"
                                data-id="{{ $comment->id }}">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger reaction-btn" data-type="angry"
                                data-id="{{ $comment->id }}">
                                <i class="fa-solid fa-face-angry"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning reaction-btn" data-type="sad"
                                data-id="{{ $comment->id }}">
                                <i class="fa-solid fa-face-frown"></i>
                            </button>
                        </div>


                        @php
                            $reactionTypes = ['like', 'dislike', 'haha', 'tym', 'angry', 'sad'];
                            $reactionIcons = [
                                'like' => 'fa-thumbs-up',
                                'dislike' => 'fa-thumbs-down',
                                'haha' => 'fa-face-laugh-squint',
                                'tym' => 'fa-heart',
                                'angry' => 'fa-face-angry',
                                'sad' => 'fa-face-frown',
                            ];
                            $reactionColors = [
                                'like' => 'primary',
                                'dislike' => 'secondary',
                                'haha' => 'warning',
                                'tym' => 'danger',
                                'angry' => 'danger',
                                'sad' => 'warning',
                            ];
                            $userReactionType = auth()->check()
                                ? optional($comment->reactions->where('user_id', auth()->id())->first())->type
                                : null;
                        @endphp

                        <div id="reaction-display-{{ $comment->id }}">
                            <div class="d-flex gap-1 mt-1">
                                @foreach ($reactionTypes as $type)
                                    @php
                                        $count = $comment->{$type . 's'}->count();
                                    @endphp

                                    @if ($count > 0)
                                        <button
                                            class="btn btn-sm d-flex align-items-center gap-1 px-2 py-1
                                                reaction-{{ $type }} border-0 rounded-pill reaction-display-btn">
                                            <i class="fa-solid {{ $reactionIcons[$type] }}"></i>
                                            <span class="{{ $type }}s-count">{{ $count }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>


                </div>
            </div>


            @if ($comment->replies && $comment->replies->count() > 0)
                <ul class="comments mt-3 fb-reply-border">
                    @foreach ($comment->replies as $reply)
                        @include('Frontend.components.comments-item', ['comment' => $reply])
                    @endforeach
                </ul>
            @endif
        </div>
    </li>
</div>

<!-- Add Modal template -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xóa bình luận này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <!-- {{-- Ghim comment --}}
                        <script>
                            $(document).on('click', '.pin-comment', function() {
                                const btn = $(this);
                                const commentId = btn.data('id');

                                if (btn.prop('disabled')) return;
                                btn.prop('disabled', true);

                                $.ajax({
                                    url: `/comments/${commentId}/pin`,
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content') // Lấy CSRF token từ meta
                                    },
                                    success: function(res) {
                                        if (res.status === 'success') {
                                            showToast(res.message, 'success');

                                            // Kiểm tra xem có phần tử comments-list không
                                            if ($('#comments-list').length) {
                                                $('#comments-list').html(res.html);
                                            } else {
                                                location.reload();
                                            }
                                        } else {
                                            showToast('Không thể ghim bình luận', 'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        let errorMessage = 'Có lỗi xảy ra';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        }
                                        showToast(errorMessage, 'error');
                                    },
                                    complete: function() {
                                        btn.prop('disabled', false);
                                    }
                                });
                            });
                        </script>
                <script>
                    $(document).ready(function() {
                        let commentToDelete = null;
                        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

                        // Khi bấm vào nút xóa
                        $('body').on('click', '.delete-comment', function() {
                            commentToDelete = $(this).data('id');
                            deleteModal.show();
                        });

                        // Khi xác nhận xóa
                        $('#confirmDelete').click(function() {
                            if (!commentToDelete) return;

                            $.ajax({
                                url: `/comments/${commentToDelete}`, // Thay vì dùng Blade route
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content') // Lấy CSRF token từ meta
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        $(`#comment-${commentToDelete}`).fadeOut(300, function() {
                                            $(this).remove();
                                        });
                                        showToast(response.message, 'success');
                                    } else {
                                        showToast(response.message, 'error');
                                    }
                                    deleteModal.hide();
                                },
                                error: function(xhr) {
                                    showToast('Có lỗi xảy ra khi xóa bình luận', 'error');
                                    console.error(xhr);
                                    deleteModal.hide();
                                }
                            });
                        });

                        // Hàm hiển thị thông báo
                        function showToast(message, type = 'info') {
                            const bgColor = type === 'success' ? 'green' : 'red';
                            $('body').append(
                                `<div class="toast-message" style="position: fixed; bottom: 10px; right: 10px; background: ${bgColor}; color: white; padding: 10px; border-radius: 5px;">${message}</div>`
                            );
                            setTimeout(() => $('.toast-message').fadeOut(500, function() {
                                $(this).remove();
                            }), 3000);
                        }
                    });
                </script> -->



        <script>
            $(document).on('click', '.pin-comment', function() {
                const btn = $(this);
                const commentId = btn.data('id');

                if (btn.prop('disabled')) return;
                btn.prop('disabled', true);

                $.ajax({
                    url: `/comments/${commentId}/pin`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            showToast(res.message, 'success', true); // Hiển thị thông báo và reload
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        } else {
                            showToast('Không thể ghim bình luận', 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Có lỗi xảy ra';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast(errorMessage, 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                    }
                });
            });
            $(document).ready(function() {
                let commentToDelete = null;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

                // Khi bấm vào nút xóa
                $('body').on('click', '.delete-comment', function() {
                    commentToDelete = $(this).data('id');
                    deleteModal.show();
                });

                // Khi xác nhận xóa
                $('#confirmDelete').click(function() {
                    if (!commentToDelete) return;

                    $.ajax({
                        url: `/comments/${commentToDelete}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showToast(response.message, 'success',
                                    true); // Hiển thị thông báo và reload
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            } else {
                                showToast(response.message, 'error');
                            }
                            deleteModal.hide();
                        },
                        error: function(xhr) {
                            showToast('Có lỗi xảy ra khi xóa bình luận', 'error');
                            deleteModal.hide();
                        }
                    });
                });
            });

            function showToast(message, type = 'info', reload = false) {
                const bgColor = type === 'success' ? 'green' : 'red';
                $('body').append(`
        <div class="toast-message" style="
            position: fixed; bottom: 10px; right: 10px;
            background: ${bgColor}; color: white; padding: 10px;
            border-radius: 5px; z-index: 9999;">
            ${message}
        </div>
    `);

                setTimeout(() => {
                    $('.toast-message').fadeOut(500, function() {
                        $(this).remove();
                        if (reload) location.reload(); // Reload trang sau khi thông báo biến mất
                    });
                }, 100);
            }
        </script>







        <!--Add existing delete modal scripts first -->
        <script>
            $(document).ready(function() {
                $(document).on('click', '.reaction-btn', function() {
                    const btn = $(this);
                    const commentId = btn.data('id');
                    const type = btn.data('type');

                    btn.prop('disabled', true); // disable trong lúc gửi

                    $.ajax({
                        url: `/comments/${commentId}/react`,
                        type: 'POST',
                        data: {
                            type: type,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Highlight nút vừa chọn
                                $(`.reaction-wrapper[data-id="${commentId}"] .reaction-btn`)
                                    .removeClass('active');
                                btn.addClass('active');

                                // Cập nhật toàn bộ icon và số lượng trong phần hiển thị
                                renderReactions(commentId, response.reactionCounts);

                                // Ẩn nhóm cảm xúc với animation
                                btn.closest('.reaction-group').fadeOut(200);

                                // Hiển thị thông báo
                                showToast(response.message, 'success');
                            } else {
                                showToast(response.message || 'Có lỗi xảy ra', 'error');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 401 && xhr.responseJSON?.redirect) {
                                window.location.href = xhr.responseJSON.redirect;
                            } else if (xhr.responseJSON?.message) {
                                showToast(xhr.responseJSON.message, 'error');
                            } else {
                                showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
                            }
                        },
                        complete: function() {
                            btn.prop('disabled', false); // bật lại sau khi xong
                        }
                    });
                });

                function renderReactions(commentId, counts) {
                    const reactionIcons = {
                        like: 'fa-thumbs-up',
                        dislike: 'fa-thumbs-down',
                        haha: 'fa-face-laugh',
                        tym: 'fa-heart',
                        angry: 'fa-face-angry',
                        sad: 'fa-face-sad-tear'
                    };

                    let html = '<div class="d-flex gap-1 mt-1">';
                    Object.keys(reactionIcons).forEach(type => {
                        const count = counts[type + 's'];
                        if (count > 0) {
                            html += `
                <button class="btn btn-sm d-flex align-items-center gap-1 px-2 py-1
                    reaction-${type} border-0 rounded-pill reaction-display-btn">
                    <i class="fa-solid ${reactionIcons[type]}"></i>
                    <span class="${type}s-count">${count}</span>
                </button>`;
                        }
                    });
                    html += '</div>';

                    $(`#reaction-display-${commentId}`).html(html);
                }


                // Cập nhật số lượng cảm xúc
                // Cập nhật toàn bộ count các loại
                function updateReactionCounts(commentId, counts) {
                    const types = ['like', 'dislike', 'haha', 'tym', 'angry', 'sad'];
                    types.forEach(type => {
                        const selector = `.reaction-wrapper[data-id="${commentId}"] .${type}s-count`;
                        $(selector).text(counts[type + 's']);

                        // Nếu reaction count = 0 thì ẩn button đó
                        const btn = $(selector).closest('.reaction-display-btn');
                        if (counts[type + 's'] == 0) {
                            btn.hide();
                        } else {
                            btn.show();
                        }
                    });
                }

                // Hiển thị thông báo
                function showToast(message, type = 'info') {
                    const bgColor = type === 'success' ? 'green' : 'red';
                    const toast = $(`
            <div class="toast-message" style="
                position: fixed; bottom: 10px; right: 10px;
                background: ${bgColor}; color: white; padding: 10px;
                border-radius: 5px; z-index: 9999;">
                ${message}
            </div>
        `);
                    $('body').append(toast);
                    setTimeout(() => {
                        toast.fadeOut(500, function() {
                            $(this).remove();
                        });
                    }, 1500);
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                // Hiện/ẩn nhóm cảm xúc khi click icon mặt cười
                $(document).on('click', '.smiley-btn', function(e) {
                    e.stopPropagation(); // Ngăn bubbling
                    const wrapper = $(this).closest('.reaction-wrapper');
                    const group = wrapper.find('.reaction-group');

                    // Ẩn tất cả group khác
                    $('.reaction-group').not(group).hide();

                    // Toggle nhóm này
                    group.toggle();
                });

                // Click ra ngoài thì ẩn hết nhóm cảm xúc
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.reaction-wrapper').length) {
                        $('.reaction-group').hide();
                    }
                });

                // Không cần reload nữa vì đã có AJAX xử lý rồi
                $(document).on('click', '.reaction-btn', function() {
                    $(this).closest('.reaction-group').hide();

                    // ❌ Đừng reload
                    // setTimeout(() => {
                    //     location.reload();
                    // }, 300);
                });
            });


            document.addEventListener("DOMContentLoaded", function() {
                const hash = window.location.hash;

                if (hash.startsWith("#comment-")) {
                    setTimeout(() => {
                        const commentElement = document.querySelector(hash);

                        if (commentElement) {
                            // Cuộn đến phần tử và đưa nó ra giữa màn hình
                            commentElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            // Highlight nhẹ để user dễ nhìn
                            commentElement.style.transition = 'background-color 0.5s ease';
                            commentElement.style.backgroundColor = '#ffffcc';

                            setTimeout(() => {
                                commentElement.style.backgroundColor = '';
                            }, 2000);
                        }
                    }, 300); // delay 300ms
                }
            });
        </script>


        <style>
            /* Avatar mặc định */
            .avatarimg {
                width: 100px;
                /* Kích thước mặc định */
                height: 100px;
                border-radius: 50%;
                /* Bo tròn avatar */
                object-fit: cover;
                /* Giữ tỷ lệ ảnh */
            }

            /* Wrapper của avatar */
            .avatar-wrapper {
                display: inline-block;
                position: relative;
                width: 100px;
                /* Kích thước mặc định */
                height: 100px;
                aspect-ratio: 1 / 1;
                /* Đảm bảo luôn là hình vuông */
            }


            /* Viền cho Admin và Mod */
            .admin-border,
            .mod-border {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                /* Không ảnh hưởng đến thao tác người dùng */
            }

            .role-badge {
                font-weight: bold;
                padding: 0 3px;
            }

            .content-badge {
                color: #cdb94f !important;
            }

            .admin-badge {
                color: #dc3545 !important;
            }

            .mod-badge {
                color: #198754 !important;
            }

            .vip-badge {
                color: #0d6efd !important;
            }

            .clickable-name {
                cursor: pointer;
                text-decoration: underline;
            }

            .clickable-name:hover {
                opacity: 0.8;
            }

            .comment {
                display: flex;
                align-items: flex-start;
                gap: 10px;
            }


            .vip-pro-badge {
                color: purple;
            }

            .vip-pro-max-badge {
                color: #f37200;
            }

            .vip-pro-sv-badge {
                background: linear-gradient(to right, #ff8a00, #ff2070);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                display: inline-block;
            }

            .text-dark {
                color: black;
            }

            /* Container chính của comment */
            .comment-container {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 10px;
                border-bottom: 1px solid #ddd;
                font-family: Arial, sans-serif;
            }

            /* Ảnh đại diện */
            .comment-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
            }

            /* Nội dung comment */
            .comment-content {
                border: 1px solid black;
                /* Viền màu đen */
                background: #2f2f2f;
                padding: 10px 14px;
                border-radius: 12px;
                max-width: 70%;
                font-size: 14px;
                position: relative;
            }

            /* Hàng phản hồi (like, reply, time) */
            .comment-actions {
                display: flex;
                gap: 10px;
                font-size: 12px;
                color: #65676b;
                cursor: pointer;
            }

            .comment-actions span:hover {
                text-decoration: underline;
            }

            /* Phản ứng biểu tượng cảm xúc */
            .reactions {
                display: flex;
                gap: 5px;
                margin-top: 4px;
            }

            .reactions img {
                width: 18px;
                height: 18px;
            }

            /* Trả lời bình luận */
            .reply-container {
                margin-left: 50px;
            }

            .blog-comment .post-comments .content-post-comments {
                border: 1px solid #eee;
                border-radius: 15px;
                padding: 5px;
                word-break: break-word;
                /* Cắt từ nếu quá dài */
                overflow-wrap: break-word;
            }

            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }

            .d-flex {
                display: flex !important;
            }

            *,
            ::after,
            ::before {
                box-sizing: border-box;

            }

            user agent stylesheet li {
                display: list-item;
                text-align: -webkit-match-parent;
                unicode-bidi: isolate;
            }

            .blog-comment ul {
                list-style-type: none;
                padding: 0;
            }


            .reaction-like {
                background-color: #0d6efd !important;
                /* xanh tươi */
                color: white !important;
            }

            .reaction-dislike {
                background-color: #6c757d !important;
                /* secondary */
                color: white !important;
            }

            .reaction-haha {
                background-color: #ffc107 !important;
                /* vàng */
                color: black !important;
            }

            .reaction-tym {
                background-color: #dc3545 !important;
                /* đỏ */
                color: white !important;
            }

            .reaction-angry {
                background-color: #fd7e14 !important;
                /* cam */
                color: white !important;
            }

            .reaction-sad {
                background-color: #ffca2a !important;
                /* vàng đậm */
                color: black !important;
            }

            #reaction-group {
                display: none !important;
            }


            .reaction-wrapper:hover .reaction-group {
                display: flex !important;
            }

            .reaction-group button {
                transition: transform 0.2s ease;
            }

            .reaction-group button:hover {
                transform: scale(1.2);
            }

            /* CSS khi hover trên desktop */
            .reaction-group button {
                transition: transform 0.2s ease;
            }

            .reaction-group button:hover {
                transform: scale(1.2);
            }

            .reaction-group {
                display: none !important;
            }


            /* CSS cho mobile */
            @media (max-width: 768px) {

                html,
                body {
                    max-width: 100vw;
                    overflow-x: hidden;
                }

                .comment-content {
                    max-width: 100% !important;
                    font-size: 13px;
                    word-break: break-word;
                    overflow-wrap: break-word;
                }

                .comment-container {
                    flex-direction: column;
                    /* Cho bố cục xuống dòng trên màn nhỏ */
                    align-items: flex-start;
                }

                .comment-avatar {
                    width: 36px;
                    height: 36px;
                }

                .reply-container {
                    margin-left: 20px;
                }

                .reactions img {
                    width: 16px;
                    height: 16px;
                }

                .reaction-group {
                    max-width: 90vw;
                    overflow-x: auto;
                    white-space: nowrap;
                    padding: 6px 8px;
                    border-radius: 8px;
                }

                .reaction-group button {
                    flex: 0 0 auto;
                    font-size: 13px;
                }

                .comment-time {
                    font-size: 12px;
                    white-space: nowrap;
                }

                .reply-btn {
                    font-size: 12px;
                    white-space: nowrap;
                }

                .reaction-display-btn {
                    display: inline-flex !important;
                    align-items: center;
                    gap: 4px;
                    white-space: nowrap;
                    padding: 4px 6px;
                    min-width: 42px;
                    /* hoặc 40px nếu bạn muốn nhỏ hơn */
                    justify-content: center;
                    font-size: 13px;
                }
            }

            .vip-super-role {
                font-size: 17px;
                font-weight: bold;
                background: linear-gradient(to right, #005f99, #87cefa, #00cc66);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                text-shadow: 1px 1px 2px rgba(0, 95, 153, 0.4);
            }

            .tooltip-icon {
                position: relative;
                display: inline-block;
                cursor: pointer;
            }

            .tooltip-icon .tooltip-text {
                visibility: hidden;
                background-color: #333;
                color: #fff;
                font-size: 12px;
                text-align: center;
                padding: 6px 10px;
                border-radius: 4px;
                position: absolute;
                z-index: 10;
                bottom: 125%;
                /* hiển thị phía trên */
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .tooltip-icon:hover .tooltip-text {
                visibility: visible;
                opacity: 1;
            }

            .fb-reply-border {
                border-left: 2px solid #e4e6eb;
                /* Màu viền giống Facebook */
                padding-left: 1rem;
                border-radius: 0 0 0 8px;
                transition: background-color 0.3s ease;
            }
        </style>
    @endpush
@endonce
