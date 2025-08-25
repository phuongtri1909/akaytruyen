@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/styles.css') }}" rel="stylesheet">
@endpush

@php
    // Sử dụng level từ database thay vì tính toán
    $level = $comment->level ?? 0;
@endphp

<div class="comment-item-wrapper" data-comment-id="{{ $comment->id }}">
    <li class="comment-item clearfix d-flex" id="comment-{{ $comment->id }}">

        @php
            $avatar =
                $comment->user && $comment->user->avatar
                    ? asset($comment->user->avatar)
                    : asset('assets/frontend/images/avatar_default.jpg');

            $role = $comment->user && $comment->user->roles && $comment->user->roles->first()
                ? $comment->user->roles->first()->name
                : null;
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

        <!-- Avatar Container with Enhanced Styling -->
        <div class="avatar-container">
            <div class="avatar-wrapper"
                style="position: relative; width: 45px; height: 45px; display: inline-block; flex-shrink: 0;">
                <!-- Ảnh Avatar -->
                <img src="{{ $avatar }}" class="user-avatar rounded-circle border border-3"
                    alt="{{ $comment->user ? $comment->user->name : 'Người dùng không tồn tại' }}"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">

                <!-- Ảnh viền nếu có -->
                @if ($border)
                    <img src="{{ $border }}" class="avatar-border rounded-circle" alt="Border {{ $role }}"
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
        </div>

        <!-- Comment Content Container -->
        <div class="post-comments p-2 p-md-3 {{ $comment->is_pinned ? 'pinned' : '' }}">
            <div class="content-post-comments">
                @php
                    $userRole = $comment->user && $comment->user->roles && $comment->user->roles->first()
                        ? $comment->user->roles->first()->name
                        : null;
                @endphp

                <!-- User Meta Information -->
                <div class="meta mb-3">
                    <div class="user-info">
                        <a class="user-name fw-bold ms-2 text-decoration-none" target="_blank">
                            @if ($comment->user)
                                @if ($userRole === 'Admin')
                                    <span class="role-badge admin-badge">
                                        @if (auth()->check() && auth()->user()->hasRole('Admin'))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none admin-badge">
                                                👑 [ADM] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            👑 [ADM] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'Mod')
                                    <span class="role-badge mod-badge">
                                        @if (auth()->check() && auth()->user()->hasRole('Admin'))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none mod-badge">
                                                🛡️ [MOD] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            🛡️ [MOD] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'vip')
                                    <span class="role-badge vip-badge">
                                        @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none vip-badge">
                                                ⭐ [VIP] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            ⭐ [VIP] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'Content')
                                    <span class="role-badge content-badge">
                                        @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none content-badge">
                                                ✍️ [CONTENT] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            ✍️ [CONTENT] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'VIP PRO')
                                    <span class="role-badge vip-pro-badge">
                                        @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none vip-pro-badge">
                                                💎 [VIP PRO] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            💎 [VIP PRO] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'VIP PRO MAX')
                                    <span class="role-badge vip-pro-max-badge">
                                        @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none vip-pro-max-badge">
                                                🔥 [VIP PRO MAX] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            🔥 [VIP PRO MAX] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @elseif ($userRole === 'VIP SIÊU VIỆT')
                                    <span class="role-badge vip-pro-sv-badge">
                                        @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                            <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                                class="text-decoration-none vip-pro-sv-badge">
                                                🌟 [VIP SIÊU VIỆT] <b>{{ $comment->user->name }}</b>
                                            </a>
                                        @else
                                            🌟 [VIP SIÊU VIỆT] <b>{{ $comment->user->name }}</b>
                                        @endif
                                    </span>
                                @else
                                    @if (auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                                        <a href="{{ route('admin.users.edit', $comment->user->id) }}" target="_blank"
                                            class="text-decoration-none text-dark">
                                            👤 <b>{{ $comment->user->name }}</b>
                                        </a>
                                    @else
                                        <span class="text-dark">👤 <b>{{ $comment->user->name }}</b></span>
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
                                <span>👤 Người dùng không tồn tại</span>
                            @endif
                        </a>
                    </div>

                    <!-- Admin Actions -->
                    <div class="admin-actions">
                        {{-- Nút xóa comment nếu user có quyền --}}
                        @if ($comment->level == 0 && auth()->check() && (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')))
                            <span class="delete-comment text-danger ms-2" style="cursor: pointer;"
                                data-id="{{ $comment->id }}" title="Xóa bình luận">
                                <i class="fas fa-trash-alt"></i>
                            </span>
                        @endif

                        {{-- Nút ghim comment chỉ Admin có quyền --}}
                        @if ($comment->level == 0 && auth()->check() && auth()->user()->hasRole('Admin'))
                            <button class="btn btn-sm pin-comment ms-2" data-id="{{ $comment->id }}" title="{{ $comment->is_pinned ? 'Bỏ ghim' : 'Ghim' }}">
                                @if ($comment->is_pinned)
                                    <i class="fas fa-thumbtack text-warning"></i>
                                @else
                                    <i class="fas fa-thumbtack"></i>
                                @endif
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Comment Content -->
                <div class="comment-content mb-3" id="comment-{{ $comment->id }}">
                    @if ($comment->user && $comment->user->hasRole('VIP SIÊU VIỆT'))
                        <div class="vip-super-role" data-text="{{ strip_tags($comment->comment) }}">
                            {!! \App\Helpers\Helper::parseLinks($comment->comment) !!}
                        </div>
                    @else
                        {!! \App\Helpers\Helper::parseLinks($comment->comment) !!}
                    @endif
                </div>

                <!-- Comment Actions -->
                <div class="comment-actions">
                    <div class="left-actions">
                        <span class="comment-time">
                            <i class="far fa-clock"></i> {{ $comment->created_at->locale('vi')->diffForHumans() }}
                        </span>

                        @if ($comment->level < 1 && auth()->check())
                            <button class="reply-btn" style="cursor: pointer;"
                                data-id="{{ $comment->id }}">
                                <i class="fa-solid fa-reply"></i> Trả lời
                            </button>
                        @endif
                    </div>

                    <!-- Reaction System -->
                    <div class="reaction-wrapper position-relative d-flex align-items-center" data-id="{{ $comment->id }}">
                        <!-- Nút mặt cười -->
                        <button class="btn btn-sm smiley-btn" title="Thêm cảm xúc">
                            <i class="fa-regular fa-face-smile"></i>
                        </button>

                        <!-- Nhóm nút reactions -->
                        <div class="reaction-group d-flex gap-1 p-1 bg-white border rounded shadow-sm">
                            <button class="btn btn-sm reaction-btn" data-type="like" data-id="{{ $comment->id }}" title="Thích">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="btn btn-sm reaction-btn" data-type="dislike" data-id="{{ $comment->id }}" title="Không thích">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                            <button class="btn btn-sm reaction-btn" data-type="haha" data-id="{{ $comment->id }}" title="Haha">
                                <i class="fa-solid fa-face-laugh-squint"></i>
                            </button>
                            <button class="btn btn-sm reaction-btn" data-type="tym" data-id="{{ $comment->id }}" title="Tim">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                            <button class="btn btn-sm reaction-btn" data-type="angry" data-id="{{ $comment->id }}" title="Giận">
                                <i class="fa-solid fa-face-angry"></i>
                            </button>
                            <button class="btn btn-sm reaction-btn" data-type="sad" data-id="{{ $comment->id }}" title="Buồn">
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

                            // Tính toán reaction counts từ eager loaded data
                            $reactionCounts = [];
                            if ($comment->reactions) {
                                foreach ($reactionTypes as $type) {
                                    $reactionCounts[$type] = $comment->reactions->where('type', $type)->count();
                                }
                            } else {
                                foreach ($reactionTypes as $type) {
                                    $reactionCounts[$type] = 0;
                                }
                            }

                            // Sử dụng eager loaded reactions thay vì query
                            $userReactionType = null;
                            if (auth()->check() && $comment->reactions) {
                                $userReaction = $comment->reactions->where('user_id', auth()->id())->first();
                                $userReactionType = $userReaction ? $userReaction->type : null;
                            }
                        @endphp

                        <div id="reaction-display-{{ $comment->id }}" class="reaction-display">
                            <div class="d-flex gap-1 mt-1">
                                @foreach ($reactionTypes as $type)
                                    @php
                                        $count = $reactionCounts[$type] ?? 0;
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

            <!-- Replies Section -->
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
                <h5 class="modal-title">⚠️ Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn xóa bình luận này?</p>
                <p class="text-muted small">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">🗑️ Xóa</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
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
                    $('.reaction-group').removeClass('show');

                    // Toggle nhóm này
                    group.toggleClass('show');
                });

                // Click ra ngoài thì ẩn hết nhóm cảm xúc
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.reaction-wrapper').length) {
                        $('.reaction-group').removeClass('show');
                    }
                });

                // Ẩn reaction group khi click vào reaction button
                $(document).on('click', '.reaction-btn', function() {
                    $(this).closest('.reaction-group').removeClass('show');
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
            /* Compact Comment Item Styles */
            .comment-item-wrapper {
                margin-bottom: 0.75rem;
                animation: fadeInUp 0.4s ease-out;
            }

            .comment-item {
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                padding: 0;
                margin: 0;
                list-style: none;
            }

            /* Avatar */
            .avatar-container {
                flex-shrink: 0;
            }

            .avatar-wrapper {
                position: relative;
                width: 45px;
                height: 45px;
                border-radius: 50%;

                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .avatar-wrapper:hover {
                transform: scale(1.02);
            }

            .user-avatar {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid #fff;
                transition: all 0.3s ease;
            }

            /* Comment Content */
            .post-comments {
                flex: 1;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                transition: all 0.3s ease;
                overflow: hidden;
                position: relative;
            }

            .post-comments:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            }

            .post-comments.pinned {
                border-left: 3px solid #ffc107;
                background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            }

            .post-comments.pinned::before {
                content: '📌';
                position: absolute;
                top: 8px;
                right: 8px;
                font-size: 1rem;
                animation: bounce 2s infinite;
            }

            .content-post-comments {
                border: none;
                border-radius: 12px;
                background: white;
            }

            /* User Meta */
            .meta {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-bottom: 0.75rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e9ecef;
            }

            .user-info {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .user-name {
                text-decoration: none;
                transition: all 0.3s ease;
            }

            .user-name:hover {
                transform: translateY(-1px);
            }

            .admin-actions {
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            /* Role Badges - Keep original colors */
            .role-badge {
                padding: 0.2rem 0.5rem;
                border-radius: 12px;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.3px;
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

            .content-badge {
                color: #cdb94f !important;
            }

            .vip-pro-badge {
                color: purple !important;
            }

            .vip-pro-max-badge {
                color: #f37200 !important;
            }

            .vip-pro-sv-badge {
                background: linear-gradient(to right, #ff8a00, #ff2070);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                display: inline-block;
            }

            /* Comment Content */
            .comment-content {
                font-size: 0.9rem;
                line-height: 1.5;
                color: #2c3e50;
                margin-bottom: 0.75rem;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

                                                .vip-super-role {
                font-size: 1rem;
                font-weight: 600;
                position: relative;
            }

            .vip-super-role::before {
                content: attr(data-text);
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #005f99, #87cefa, #00cc66);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                pointer-events: none;
                z-index: 1;
            }

            .vip-super-role img,
            .vip-super-role .emoji,
            .vip-super-role a {
                position: relative;
                z-index: 2;
                background: none !important;
                -webkit-text-fill-color: unset !important;
                filter: none !important;
                display: inline-block;
            }

            /* Actions */
            .comment-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 0.75rem;
            }

            .left-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                flex-wrap: wrap;
            }

            .comment-time {
                font-size: 0.75rem;
                color: #6c757d;
                font-style: italic;
                display: flex;
                align-items: center;
                gap: 0.2rem;
            }

            .reply-btn {
                color: #2c3e50;
                font-size: 0.8rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                padding: 0.25rem 0.5rem;
                border-radius: 12px;
                text-decoration: none;
                border: 1px solid #2c3e50;
                background: transparent;
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .reply-btn:hover {
                background: rgba(0, 123, 255, 0.1);
                color: #0056b3;
            }

            /* Reaction System - Fixed */
            .reaction-wrapper {
                position: relative;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            /* Reset any conflicting CSS */
            .reaction-wrapper .reaction-group {
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            .reaction-wrapper .reaction-group.show {
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            .smiley-btn {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                color: #6c757d;
                font-size: 0.8rem;
            }

            .smiley-btn:hover {
                background: #e9ecef;
                transform: scale(1.05);
            }

            .reaction-group {
                position: absolute;
                bottom: 120%;
                left: -200px;
                background: white;
                border-radius: 15px;
                padding: 0.5rem;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
                z-index: 1000;
                animation: slideInUp 0.2s ease-out;
            }

            .reaction-btn {
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                border: 1px solid transparent;
                margin: 0 0.2rem;
                background: #f8f9fa;
                color: #6c757d;
                font-size: 0.8rem;
            }

            .reaction-btn:hover {
                transform: scale(1.1);
                border-color: #007bff;
            }

            .reaction-display {
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .reaction-display-btn {
                background: rgba(0, 123, 255, 0.1);
                border: 1px solid rgba(0, 123, 255, 0.2);
                border-radius: 12px;
                padding: 0.2rem 0.4rem;
                font-size: 0.75rem;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.2rem;
            }

            .reaction-display-btn:hover {
                background: rgba(0, 123, 255, 0.15);
            }

            /* Reaction Colors */
            .reaction-like {
                background-color: #0d6efd !important;
                color: white !important;
            }

            .reaction-dislike {
                background-color: #6c757d !important;
                color: white !important;
            }

            .reaction-haha {
                background-color: #ffc107 !important;
                color: black !important;
            }

            .reaction-tym {
                background-color: #dc3545 !important;
                color: white !important;
            }

            .reaction-angry {
                background-color: #fd7e14 !important;
                color: white !important;
            }

            .reaction-sad {
                background-color: #ffca2a !important;
                color: black !important;
            }

            /* Admin Actions */
            .delete-comment {
                color: #dc3545;
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 50%;
                transition: all 0.3s ease;
                background: transparent;
                border: none;
                font-size: 0.8rem;
            }

            .delete-comment:hover {
                background: rgba(220, 53, 69, 0.1);
                transform: scale(1.05);
            }

            .pin-comment {
                background: transparent;
                border: none;
                color: #6c757d;
                transition: all 0.3s ease;
                padding: 0.25rem;
                border-radius: 50%;
                font-size: 0.8rem;
            }

            .pin-comment:hover {
                background: rgba(255, 193, 7, 0.1);
                color: #ffc107;
                transform: scale(1.05);
            }

            .pin-comment .text-warning {
                animation: pulse 2s infinite;
            }

            /* Tooltip */
            .tooltip-icon {
                position: relative;
                display: inline-block;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .tooltip-icon:hover {
                transform: scale(1.05);
            }

            .tooltip-icon .tooltip-text {
                visibility: hidden;
                background: #2c3e50;
                color: white;
                font-size: 0.7rem;
                text-align: center;
                padding: 0.4rem 0.6rem;
                border-radius: 6px;
                position: absolute;
                z-index: 1000;
                bottom: 125%;
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                opacity: 0;
                transition: all 0.3s ease;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            }

            .tooltip-icon:hover .tooltip-text {
                visibility: visible;
                opacity: 1;
                transform: translateX(-50%) translateY(-3px);
            }

            /* Reply Section */
            .fb-reply-border {
                border-left: 2px solid #e4e6eb;
                padding-left: 0.5rem;
                border-radius: 0 0 0 6px;
                transition: background-color 0.3s ease;
                margin-top: 0.75rem;
            }

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.03); }
                100% { transform: scale(1); }
            }

            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-8px);
                }
                60% {
                    transform: translateY(-4px);
                }
            }

            /* Mobile */
            @media (max-width: 768px) {
                .comment-item {
                    gap: 0.5rem;
                }

                .avatar-wrapper {
                    width: 40px;
                    height: 40px;
                }

                .meta {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.3rem;
                }

                .user-info {
                    gap: 0.2rem;
                }

                .role-badge {
                    font-size: 0.65rem;
                    padding: 0.15rem 0.4rem;
                }

                .comment-actions {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 0.5rem;
                }

                .left-actions {
                    gap: 0.5rem;
                }

                .reaction-group {
                    left: -80px;
                    max-width: 250px;
                }

                .reaction-btn {
                    width: 28px;
                    height: 28px;
                    font-size: 0.75rem;
                }

                .reply-btn {
                    padding: 0.2rem 0.4rem;
                    font-size: 0.75rem;
                }

                .comment-time {
                    font-size: 0.7rem;
                }
            }
        </style>
    @endpush
@endonce
