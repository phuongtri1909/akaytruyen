{{-- Comments List Container --}}
<div class="comments-list-container">
    {{-- Show pinned comments first with special styling --}}
    @if($pinnedComments->count() > 0)
        <div class="pinned-comments-section">
            <div class="pinned-header">
                <h6 class="pinned-title">
                    📌 Bình luận được ghim
                </h6>
            </div>
            <div class="pinned-comments">
                @foreach($pinnedComments as $comment)
                    @include('Frontend.components.comments-item', ['comment' => $comment])
                @endforeach
            </div>
        </div>
    @endif

    {{-- Show regular comments --}}
    @if($regularComments->count() > 0)
        <div class="regular-comments-section">
            @if($pinnedComments->count() > 0)
                <div class="section-divider">
                    <span class="divider-text">💬 Tất cả bình luận</span>
                </div>
            @endif
            <div class="regular-comments">
                @foreach($regularComments as $comment)
                    @include('Frontend.components.comments-item', ['comment' => $comment])
                @endforeach
            </div>
        </div>
    @endif

    {{-- Empty state --}}
    @if($pinnedComments->count() == 0 && $regularComments->count() == 0)
        <div class="empty-comments">
            <div class="empty-icon">💭</div>
            <p class="empty-text">Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ suy nghĩ!</p>
        </div>
    @endif
</div>

<style>
    .comments-list-container {
        margin-top: 0.75rem;
    }

    .pinned-comments-section {
        margin-bottom: 1.5rem;
    }

    .pinned-header {
        margin-bottom: 0.75rem;
    }

    .pinned-title {
        color: #ffc107;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin: 0;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border-radius: 15px;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(255, 193, 7, 0.15);
    }

    .pinned-comments {
        border-left: 2px solid #ffc107;
        padding-left: 0.75rem;
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.03), rgba(255, 234, 167, 0.08));
        border-radius: 0 8px 8px 0;
        padding: 0.75rem 0.75rem 0.75rem 1rem;
    }

    .section-divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e9ecef, transparent);
    }

    .divider-text {
        background: white;
        padding: 0 0.75rem;
        color: #6c757d;
        font-size: 0.8rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .regular-comments-section {
        margin-top: 0.75rem;
    }

    .regular-comments {
        animation: fadeInUp 0.4s ease-out;
    }

    .empty-comments {
        text-align: center;
        padding: 2rem 1rem;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .empty-text {
        font-size: 0.9rem;
        margin: 0;
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        .pinned-comments {
            padding: 0.5rem 0.5rem 0.5rem 0.75rem;
        }

        .pinned-title {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }

        .divider-text {
            font-size: 0.75rem;
        }

        .empty-comments {
            padding: 1.5rem 1rem;
        }

        .empty-icon {
            font-size: 2rem;
        }

        .empty-text {
            font-size: 0.85rem;
        }
    }
</style>
