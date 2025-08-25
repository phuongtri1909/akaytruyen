@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/styles.css') }}" rel="stylesheet">
    <style>
        .role-badge {
            font-weight: bold;
            padding: 0 3px;
        }

        .admin-badge {
            color: #dc3545;
        }

        .mod-badge {
            color: #198754;
        }

        .vip-badge {
            color: #0d6efd;
        }

        .clickable-name {
            cursor: pointer;
            text-decoration: underline;
        }

        .clickable-name:hover {
            opacity: 0.8;
        }
    </style>
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

<li class="clearfix d-flex" id="comment-{{ $comment->id }} ">
    <img src="{{ $comment->user && $comment->user->avatar ? asset($comment->user->avatar) : asset('assets/frontend/images/avatar_default.jpg') }}"
        class=" {{ $comment->level > 0 ? 'avatar-reply' : 'avatar' }}"
        alt="{{ $comment->user ? $comment->user->name : 'Người dùng không tồn tại' }}" style="width: 59px;height: 59px;border-radius: 74px;">
    <div class="post-comments p-2 p-md-3">
        <div class="content-post-comments name_">
            <p class="meta mb-2">

                <a class="fw-bold ms-2 text-decoration-none" target="_blank">
                    @if ($comment->user)
                          @if ($comment->user->hasRole('Admin'))

                            <span class="role-badge admin-badge">
                                @if(auth()->check() && auth()->user()->roles === 'admin')
                                    <a href="{{ route('users.show', $comment->user->id) }}" target="_blank" class="text-decoration-none admin-badge">
                                        [ADMIN] {{ $comment->user->name }}
                                    </a>
                                @else
                                    [ADMIN] {{ $comment->user->name }}
                                @endif
                            </span>
                        @elseif($comment->user->hasRole('Mod'))
                            <span class="role-badge mod-badge">
                                @if(auth()->check() && auth()->user()->roles === 'admin')
                                    <a href="{{ route('users.show', $comment->user->id) }}" target="_blank" class="text-decoration-none mod-badge">
                                        [MOD] {{ $comment->user->name }}
                                    </a>
                                @else
                                    [MOD] {{ $comment->user->name }}
                                @endif
                            </span>
                        @elseif($comment->user->hasRole('Vip'))
                            <span class="role-badge vip-badge">
                                @if(auth()->check() && (auth()->user()->role === 'Admin' || auth()->user()->role === 'Mod'))
                                    <a href="{{ route('users.show', $comment->user->id) }}" target="_blank" class="text-decoration-none vip-badge">
                                        [VIP] {{ $comment->user->name }}
                                    </a>
                                @else
                                    [VIP] {{ $comment->user->name }}
                                @endif
                            </span>
                        @else
                            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'mod'))
                                <a href="{{ route('users.show', $comment->user->id) }}" target="_blank" class="text-decoration-none text-dark">
                                    {{ $comment->user->name }}
                                </a>
                            @else
                                <span class="text-dark">{{ $comment->user->name }}</span>
                            @endif
                        @endif
                    @else
                        <span>Người dùng không tồn tại</span>
                    @endif
                </a>

                @if ($comment->level < 2 && auth()->check())
                    <span class="pull-right">
                        <small class="reply-btn text-decoration-underline" style="cursor: pointer;"
                            data-id="{{ $comment->id }}">
                            Trả lời
                        </small>
                    </span>
                @endif


                @if (
                    auth()->check() && (
                        auth()->user()->roles->contains('name', 'Admin') ||
                        auth()->user()->roles->contains('name', 'Mod')
                    )
                )
                    <span class="livechat-delete-comment text-danger ms-2" style="cursor: pointer;" data-id="{{ $comment->id }}">
                        <i class="fas fa-times"></i>
                    </span>
                @endif

                @if ($comment->level == 0 && auth()->check() && (
                        auth()->user()->roles->contains('name', 'Admin') ||
                        auth()->user()->roles->contains('name', 'Mod')
                    ))
                    <button class="btn btn-sm livechat-pin-comment ms-2" data-id="{{ $comment->id }}">
                        @if($comment->is_pinned)
                            <i class="fas fa-thumbtack text-warning" title="Bỏ ghim"></i>
                        @else
                            <i class="fas fa-thumbtack" title="Ghim"></i>
                        @endif
                    </button>
                @endif
            </p>

            <p class="mb-2">{{ $comment->comment }}</p>
        </div>

        @if ($comment->replies && $comment->replies->count() > 0)
            <ul class="comments mt-3">
                @foreach ($comment->replies as $reply)
                    @include('Frontend.sections.components.comments-item', ['comment' => $reply])
                @endforeach
            </ul>
        @endif
    </div>
</li>


<!-- Add Modal template -->
<div class="modal fade" id="livechatDeleteModal" tabindex="-1">
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
                <button type="button" class="btn btn-danger" id="livechatConfirmDelete">Xóa</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        {{-- Ghim comment --}}
        <script>
            $(document).on('click', '.livechat-pin-comment', function() {
                const btn = $(this);
                const commentId = btn.data('id');

                if (btn.prop('disabled')) return;
                btn.prop('disabled', true);

                $.ajax({
                    url: `/live/${commentId}/pin`,
                    type: 'POST',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(res) {
                        if (res.status === 'success') {
                            // Update comments list with new HTML
                            $('#comments-list').html(res.html);
                            // showToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        // showToast(xhr.responseJSON.message || 'Có lỗi xảy ra', 'error');
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
                const deleteModal = new bootstrap.Modal(document.getElementById('livechatDeleteModal'));

                $('body').on('click', '.livechat-delete-comment', function() {
                    commentToDelete = $(this).data('id');
                    deleteModal.show();
                });

                $('#livechatConfirmDelete').click(function() {
                    if (!commentToDelete) return;

                    $.ajax({
                        url: '{{ route('delete.comments', '') }}/' + commentToDelete,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                $(`#comment-${commentToDelete}`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                                // showToast('Đã xóa bình luận thành công');
                            }
                            deleteModal.hide();
                        },
                        error: function(xhr) {
                            console.log(xhr);
                            // showToast('Có lỗi xảy ra khi xóa bình luận', 'error');
                            deleteModal.hide();
                        }
                    });
                });
            });
        </script>

        <!-- Add existing delete modal scripts first -->
        <script>
            $('.livechat-reaction-btn').click(function() {
                const btn = $(this);
                const commentId = btn.data('id');
                const type = btn.data('type');

                $.ajax({
                    url: `/comments/${commentId}/live`,
                    type: 'POST',
                    data: {
                        type: type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            btn.find(type === 'like' ? '.likes-count' : '.dislikes-count').text(response[
                                type + 's']);
                            btn.toggleClass('active');
                            showToast(response.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = xhr.responseJSON.redirect;
                        } else {
                            showToast('Có lỗi xảy ra', 'error');
                        }
                    }
                });
            });
        </script>
    @endpush
@endonce
