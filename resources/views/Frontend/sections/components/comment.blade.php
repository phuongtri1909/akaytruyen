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
    </style>
@endpush
<section id="comments" class="my-3 my-md-5">
    <div class="container px-2 px-md-3">
        <div class="row">
            <div class="col-12">
                <div class="form-floating submit-comment">
                    <textarea class="form-control" id="comment-input" placeholder="Nhập bình luận..." rows="2" maxlength="700"></textarea>
                    <label for="comment-input">Bình luận</label>
                    <button class="btn btn-sm btn-outline-info btn-send-comment" id="btn-comment">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>

                <div class="blog-comment">
                    <ul class="comments mb-0" id="comments-list">
                        @include('Frontend.sections.components.comments-list', ['pinnedComments' => $pinnedComments, 'regularComments' => $regularComments])
                    </ul>
                </div>

                @if ($regularComments->hasMorePages())
                    <div class="text-center mt-3">
                        <button class="btn btn-link" id="load-more-comments">
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
        $(document).ready(function() {
            let page = 1;
            let isSubmitting = false;


            $('#btn-comment').click(function() {
                const btn = $(this);
                const comment = $('#comment-input').val().trim();
                let reply_id = $(this).data("reply-id") || null;
                if (!comment || isSubmitting) return;

                // Disable button and show loading
                isSubmitting = true;
                btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: '{{ route('user.comment.store.client') }}',
                    type: 'POST',
                    data: {
                        comment: comment,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            $('#comments-list').prepend(res.html);
                            $('#comment-input').val('');
                            showToast(res.message, 'success');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            window.location.href = '{{ route('login') }}';
                        } else {
                            showToast(xhr.responseJSON.message || 'Có lỗi xảy ra', 'error');
                        }
                    },
                    complete: function() {
                        // Re-enable button and restore original state
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

            $(document).on('click', '.reply-btn', function(e) {
                e.preventDefault();
                const commentId = $(this).data('id');
                const replyForm = `
            <div class="reply-form mt-2">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Nhập trả lời..." maxlength="700"></textarea>
                    <label>Trả lời</label>
                </div>
                <button class="btn btn-sm btn-info mt-2 submit-reply" data-id="${commentId}">Gửi</button>
            </div>
        `;
                $(this).closest('.post-comments').append(replyForm);
                $(this).hide();
            });

            $(document).on('click', '.submit-reply', function() {
                const btn = $(this);
                const commentId = btn.data('id');
                const reply = btn.closest('.reply-form').find('textarea').val().trim();

                if (!reply || btn.prop('disabled')) return;

                // Disable button and show loading
                btn.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: '{{ route('live.comment.store.client') }}',
                    type: 'POST',
                    data: {
                        comment: reply,
                        reply_id: commentId,
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
@endpush
