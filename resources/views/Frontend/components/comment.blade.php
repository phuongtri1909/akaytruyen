@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/comment-styles.css') }}" rel="stylesheet">
@endpush
<section id="comments" class="comment-section">
    <div class="container px-2 px-md-3">
        <h5 class="mb-3">💬 BÌNH LUẬN TRUYỆN</h5>
        <div class="row">
            <div class="col-12">
                <div class="comment-input-container">
                    <div class="form-floating submit-comment">
                        <textarea class="form-control" id="comment-input" placeholder="Chia sẻ suy nghĩ của bạn..." rows="2" maxlength="700"></textarea>
                        <label for="comment-input">✍️ Viết bình luận...</label>
                        <button class="btn btn-sm btn-send-comment" id="btn-comment" value="{{ $chapter->id ?? '' }}">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>
                </div>

                <div class="blog-comment">
                    <ul class="comments mb-0 px-0" id="comments-list">
                        @include('Frontend.components.comments-list', ['pinnedComments' => $pinnedComments, 'regularComments' => $regularComments])
                    </ul>
                </div>

                @if (method_exists($regularComments, 'hasMorePages') && $regularComments->hasMorePages())
                    <div class="load-more-container">
                        <button class="btn btn-link" id="load-more-comments" data-next-page="{{ $regularComments->nextPageUrl() }}">
                            📄 Xem thêm bình luận...
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="commentDeleteModal" tabindex="-1" aria-labelledby="commentDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentDeleteModalLabel">⚠️ Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn xóa bình luận này?</p>
                <p class="text-muted small">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                <button type="button" class="btn btn-danger" id="commentConfirmDelete">🗑️ Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit History Modal -->
<div class="modal fade" id="editHistoryModal" tabindex="-1" aria-labelledby="editHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHistoryModalLabel">
                    <i class="fas fa-history"></i> Lịch sử chỉnh sửa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editHistoryContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@include('Frontend.components.comment-edit-history-modal')

@push('scripts')
    <script src="{{ asset('assets/frontend/js/comment-functions.js') }}"></script>
    <script>
        // Function to load CSS and JS assets for new comments
        function loadCommentAssets() {
            // Load CSS files if not already loaded
            if (!$('link[href*="comment-styles.css"]').length) {
                $('head').append('<link href="{{ asset("assets/frontend/css/comment-styles.css") }}" rel="stylesheet">');
            }

            // Reset initialization flags to ensure new comments get proper functionality
            if (typeof resetCommentInitialization === 'function') {
                resetCommentInitialization();
            }

            // Only initialize reaction functionality for new comments
            // Edit and delete functionality are already bound once and don't need re-initialization
            if (typeof initializeReactionFunctionality === 'function') {
                initializeReactionFunctionality();
            }
        }

        // Initialize comment functionality when script loads
        $(document).ready(function() {
            // Load CSS files if not already loaded
            if (!$('link[href*="comment-styles.css"]').length) {
                $('head').append('<link href="{{ asset("assets/frontend/css/comment-styles.css") }}" rel="stylesheet">');
            }

            // Initialize all functionality once on page load
            if (typeof initializeCommentFunctionality === 'function') {
                initializeCommentFunctionality();
            }
            if (typeof initializeReactionFunctionality === 'function') {
                initializeReactionFunctionality();
            }
            if (typeof initializeSmileyAndEditFunctionality === 'function') {
                initializeSmileyAndEditFunctionality();
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            let page = 1;
            let isSubmitting = false;

            $('#btn-comment').click(function() {
    const chapter_id = window.location.pathname.split('/').pop(); // lấy ID từ URL
    console.log("Chapter ID:", chapter_id);

    let reply_id = $(this).data("reply-id") || null;

    const btn = $(this);
    const comment = $('#comment-input').val().trim();

    if (!comment || isSubmitting) return;

    isSubmitting = true;
    btn.prop('disabled', true)
    .html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: '{{ route('comment.store.client') }}',
        type: 'POST',
        data: {
            comment: comment,
            chapter_id: chapter_id,
            reply_id: reply_id,
            _token: '{{ csrf_token() }}'
        },
        success: function(res) {
            if (res.status === 'success') {
                $('#comments-list').prepend(res.html);
                $('#comment-input').val('');

                // Hide empty state if it exists
                $('.empty-comments').fadeOut(300, function() {
                    $(this).remove();
                });

                // Load CSS and JS for the new comment
                loadCommentAssets();

                // showToast(res.message, 'success');
            }
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                window.location.href = '{{ route('login') }}';
            }
        },
        complete: function() {
            isSubmitting = false;
            btn.prop('disabled', false)
            .html('<i class="fa-regular fa-paper-plane"></i>');
        }
    });
});

            $('#load-more-comments').click(function() {
                page++;
                $.ajax({
                    url: '{{ route('home') }}',
                    data: {
                        page: page,
                        type: 'comments'
                    },
                    success: function(res) {
                        $('#comments-list').append(res.html);

                        // Load CSS and JS for loaded comments
                        loadCommentAssets();

                        if (!res.hasMore) {
                            $('#load-more-comments').remove();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);

                    }
                });
            });

            $(document).on('click', '.reply-btn', function (e) {
                e.preventDefault();
                const commentId = $(this).data('id');
                const userName = $(this).closest('.post-comments').find('.meta b').text().trim();

                if ($(this).closest('.post-comments').find('.reply-form').length === 0) {
                    const replyForm = `
                        <div class="reply-form mt-2">
                            <div class="form-floating">
                                <textarea class="form-control reply-text" placeholder="Nhập trả lời..." maxlength="700"></textarea>
                                <label>Trả lời</label>
                                <ul class="mention-list"></ul>
                            </div>
                            <button class="btn btn-sm btn-info mt-2 submit-reply" data-id="${commentId}">Gửi</button>
                        </div>
                    `;
                    $(this).closest('.post-comments').append(replyForm);
                    $(this).hide();
                }
            });
             // Gợi ý người dùng khi nhập "@"

// Khi người dùng gõ @
$(document).on('input', '.reply-text', function () {
    const input = $(this);
    const text = input.val();
    const mentionList = input.closest('.reply-form').find('.mention-list');

    if (text.includes('@')) {
    const atIndex = text.lastIndexOf('@');
    const colonIndex = text.indexOf(':');

    // Chỉ tìm nếu @ nằm sau dấu :
    if (colonIndex === -1 || atIndex > colonIndex) {
        const query = text.substring(atIndex + 1).trim();
        if (query.length > 0) {
            $.ajax({
                url: "/search-users",
                type: "GET",
                data: { query: query },
                success: function (res) {
                    mentionList.empty().show();
                    if (res.users.length === 0) {
                        mentionList.append(`<li class="mention-item">Không tìm thấy</li>`);
                    } else {
                        res.users.forEach(user => {
                            mentionList.append(`<li class="mention-item" data-name="${user.name}"><b>@${user.name}</b></li>`);
                        });
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    } else {
        mentionList.hide();
    }
} else {
    mentionList.hide();
}

});



// Khi người dùng chọn tên
$(document).on('click', '.mention-item', function () {
    const mention = $(this).data('name'); // username
    const textarea = $(this).closest('.reply-form').find('.reply-text');
    const currentText = textarea.val();

    // Tìm vị trí từ cuối cùng có dấu @
    const lastAtIndex = currentText.lastIndexOf('@');
    if (lastAtIndex !== -1) {
        const beforeAt = currentText.substring(0, lastAtIndex);
        const formattedMention = `@${mention}: `;
        textarea.val(beforeAt + formattedMention);
    }

    $(this).parent().hide(); // Ẩn danh sách gợi ý
    textarea.focus();
});





            $(document).on('click', '.submit-reply', function() {
                const chapter_id = {{ $chapter->id }};
                console.log("Chapter ID:", chapter_id);
                let reply_id = $(this).data("reply-id") || null;
                const btn = $(this);
                const commentId = btn.data('id');
                const reply = btn.closest('.reply-form').find('textarea').val().trim();

                if (!reply || btn.prop('disabled')) return;

                // Disable button and show loading
                btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: '{{ route('comment.store.client') }}',
                    type: 'POST',
                    data: {
                        comment: reply,
                        reply_id: commentId,
                        chapter_id: chapter_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            let replyContainer = btn.closest('.post-comments').find(
                                'ul.comments');
                            let replyBtn = btn.closest('.post-comments').find('.reply-btn');

                            // Create replies container if it doesn't exist
                            if (replyContainer.length === 0) {
                                btn.closest('.post-comments').append(
                                    '<ul class="comments"></ul>');
                                replyContainer = btn.closest('.post-comments').find(
                                    'ul.comments');
                            }

                            replyContainer.append(res.html);
                            btn.closest('.reply-form').remove();

                            // Re-enable reply button with delay to ensure DOM is updated
                            setTimeout(() => {
                                replyBtn.css('display', 'inline-block');
                            }, 100);

                            // Load CSS and JS for new reply
                            loadCommentAssets();

                            showToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        showToast(xhr.responseJSON.message || 'Có lỗi xảy ra', 'error');
                        // Re-enable button on error
                        btn.prop('disabled', false).text('Gửi');
                    }
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tributejs/5.1.3/tribute.min.js"></script>
    <script>
        // Global toast function
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
                    if (reload) location.reload();
                });
            }, 100);
        }
    </script>

    <script>
                            // Function to load CSS and JS assets for new comments
                    function loadCommentAssets() {
                        // Load CSS files if not already loaded
                        if (!$('link[href*="comment-styles.css"]').length) {
                            $('head').append('<link href="{{ asset("assets/frontend/css/comment-styles.css") }}" rel="stylesheet">');
                        }

                        // Reset initialization flags to ensure new comments get proper functionality
                        if (typeof resetCommentInitialization === 'function') {
                            resetCommentInitialization();
                        }

                        // Initialize comment functionality for new comments
                        if (typeof initializeCommentFunctionality === 'function') {
                            initializeCommentFunctionality();
                        }
                        if (typeof initializeReactionFunctionality === 'function') {
                            initializeReactionFunctionality();
                        }
                        if (typeof initializeSmileyAndEditFunctionality === 'function') {
                            initializeSmileyAndEditFunctionality();
                        }
                    }

        // Load assets on page load
        $(document).ready(function() {
            loadCommentAssets();
        });
    </script>
@endpush
