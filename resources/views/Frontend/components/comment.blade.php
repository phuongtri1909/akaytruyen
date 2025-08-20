@push('styles')
<style>
        .blog-comment ul.comments ul {
            position: relative;
        }

        .blog-comment ul.comments ul:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 0;
            height: 100%;
            border-left: 2px solid #eee;
        }

        .blog-comment ul.comments ul li:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 20px;
            width: 25px;
            border-top: 2px solid #eee;
        }

        .blog-comment ul.comments ul li {
            position: relative;
        }

        @media (max-width: 768px) {

            .blog-comment ul.comments ul:before {
                left: -10px;
            }

            .blog-comment ul.comments ul li:before {
                left: -10px;
                width: 10px;
            }
        }

        /* comment */
        .blog-comment::before,
        .blog-comment::after,
        .blog-comment-form::before,
        .blog-comment-form::after {
            content: "";
            display: table;
            clear: both;
        }

        .blog-comment ul {
            list-style-type: none;
            padding: 0;
        }

        .blog-comment img {
            opacity: 1;
            filter: Alpha(opacity=100);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            -o-border-radius: 4px;
            border-radius: 4px;
        }

        .blog-comment img.avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .blog-comment img.avatar-reply {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .blog-comment img.avatar {
                width: 35px;
                height: 35px;
            }

            .blog-comment img.avatar-reply {
                width: 25px;
                height: 25px;
            }
        }

        .blog-comment .post-comments {

            background: #fff;

            margin-bottom: 15px;
            position: relative;
        }

        .blog-comment .post-comments .content-post-comments {
            border: 1px solid #eee;
            border-radius: 15px;
            padding: 5px;
        }

        .blog-comment .meta {
            font-size: 13px;
            color: #aaa;
            padding-bottom: 8px;
            margin-bottom: 10px !important;
            border-bottom: 1px solid #eee;
        }



        .blog-comment-form {
            padding-left: 15%;
            padding-right: 15%;
            padding-top: 40px;
        }

        .blog-comment h3,
        .blog-comment-form h3 {
            margin-bottom: 40px;
            font-size: 26px;
            line-height: 30px;
            font-weight: 800;
        }

        .submit-comment {
            position: relative;
            margin-bottom: 20px;
        }

        .btn-send-comment {
            position: absolute;
            right: 12px;
            bottom: 8px;
        }

        .reaction-btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .reply-form {
            margin: 10px 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .blog-comment .post-comments {
                padding: 10px !important;
            }

            .blog-comment img.avatar {
                width: 35px;
                height: 35px;
            }

            .reaction-btn {
                padding: 2px 6px;
            }

            .btn-send-comment {
                bottom: 4px;
            }

            .meta {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                align-items: center;
            }

            .meta .pull-right {
                margin-left: auto;
            }
        }
        .mention-list {
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 250px;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 5px 0;
    display: none;
    z-index: 1000;
}

.mention-item {
    display: flex;
    align-items: center;
    padding: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.mention-item:hover {
    background: #f5f5f5;
}

.mention-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 10px;
}

.mention-name {
    font-weight: 600;
    color: #333;
}


    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tributejs/5.1.3/tribute.css">


@endpush
<section id="comments" class="my-3 my-md-5">
    <div class="container px-2 px-md-3">
        <h5 class="mb-3">BÌNH LUẬN TRUYỆN</h5>
        <div class="row">
            <div class="col-12">
                <div class="form-floating submit-comment">
                    <textarea class="form-control" id="comment-input" placeholder="Nhập bình luận..." rows="2" maxlength="700"></textarea>
                    <label for="comment-input">Bình luận</label>
                    <button class="btn btn-sm btn-outline-info btn-send-comment" id="btn-comment" value="{{ $chapter->id ?? '' }}">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>

                <div class="blog-comment">
                    <ul class="comments mb-0" id="comments-list">
                        @include('Frontend.components.comments-list', ['pinnedComments' => $pinnedComments, 'regularComments' => $regularComments])
                    </ul>
                </div>

                @if (method_exists($regularComments, 'hasMorePages') && $regularComments->hasMorePages())
    <div class="text-center mt-3">
        <button class="btn btn-link" id="load-more-comments" data-next-page="{{ $regularComments->nextPageUrl() }}">
            Xem thêm bình luận...
        </button>
    </div>
@endif

            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let loadMoreBtn = document.getElementById("load-more-comments");

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", function () {
                let nextPageUrl = this.getAttribute("data-next-page");

                if (!nextPageUrl) return;

                fetch(nextPageUrl)
                    .then(response => response.text())
                    .then(data => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, "text/html");

                        // Lấy danh sách bình luận mới từ trang tiếp theo
                        let newComments = doc.getElementById("comments-list").innerHTML;
                        document.getElementById("comments-list").insertAdjacentHTML("beforeend", newComments);

                        // Cập nhật URL của nút nếu còn trang tiếp theo
                        let newNextPageUrl = doc.getElementById("load-more-comments")?.getAttribute("data-next-page");
                        if (newNextPageUrl) {
                            loadMoreBtn.setAttribute("data-next-page", newNextPageUrl);
                        } else {
                            loadMoreBtn.remove(); // Ẩn nút nếu không còn trang
                        }
                    })
                    .catch(error => console.error("Lỗi tải bình luận:", error));
            });
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
@endpush
