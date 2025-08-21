@php
    /** @var \App\Models\Livechat $comment */
    $authUser = auth()->user();
    $isOwnerOrStaff = $authUser && (
        $comment->user_id === $authUser->id ||
        $authUser->hasRole(['Admin', 'Mod'])
    );
    $isVipSieuViet = $comment->user?->hasRole('VIP SIÊU VIỆT');
    $replyCount = $comment->replies->count();
@endphp

<div class="border p-3 mb-2 rounded bg-light comment-item">
    {{-- Header --}}
    <div class="d-flex align-items-center">
        @include('Frontend.components.user-avatar', ['user' => $comment->user])

        <div class="ms-2 flex-grow-1">
            @include('Frontend.components.user-badge', ['user' => $comment->user])

            {{-- Pin comment --}}
            @if ($authUser && $authUser->hasRole(['Admin', 'Mod']) && !$comment->parent_id)
                <button wire:click="pinComment({{ $comment->id }})"
                        class="btn btn-sm me-2" title="{{ $comment->pinned ? 'Bỏ ghim' : 'Ghim' }}">
                    <i class="fas fa-thumbtack {{ $comment->pinned ? 'text-warning' : '' }}"></i>
                </button>
            @endif
        </div>
    </div>

    {{-- Meta --}}
    <span class="ms-5 text-muted">
        - {{ $comment->created_at->locale('vi')->diffForHumans() }}
    </span>

    {{-- Nội dung --}}
    <p class="break-words mt-2 {{ $isVipSieuViet ? 'vip-sieu-viet-content' : '' }}">
        {!! \App\Helpers\Helper::parseLinks($comment->content) !!}
    </p>

    {{-- Actions --}}
    <div class="d-flex">
        @if ($authUser)
            <button wire:click="$set('parent_id', {{ $comment->id }})"
                    class="btn btn-sm btn-outline-secondary me-2">
                Trả lời
            </button>

            @if ($isOwnerOrStaff)
                <button onclick="confirmDelete({{ $comment->id }})"
                        class="btn btn-sm btn-outline-danger">
                    Xóa
                </button>
            @endif
        @endif
    </div>

    {{-- Reply form --}}
    @if ($parent_id === $comment->id)
        @if (!$authUser?->ban_comment)
            <div class="mt-3 p-2 bg-light rounded">
                <textarea wire:model.lazy="content" class="form-control"
                          placeholder="Viết phản hồi..." rows="2" maxlength="1000"></textarea>
                <div class="mt-2">
                    <button wire:click="postComment"
                            class="btn btn-primary btn-sm me-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="postComment">Gửi</span>
                        <span wire:loading wire:target="postComment">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button wire:click="$set('parent_id', null)"
                            class="btn btn-secondary btn-sm">Hủy</button>
                </div>
            </div>
        @else
            <p class="text-danger mt-2">Bạn đã bị cấm bình luận.</p>
        @endif
    @endif

    {{-- Replies --}}
    @if ($replyCount > 0)
        @foreach ($comment->replies->take(5) as $reply)
            @if ($reply->user)
                <div class="fb-reply-border mt-3">
                    <div class="d-flex align-items-center">
                        @include('Frontend.components.user-avatar', ['user' => $reply->user])
                        <div class="ms-2 flex-grow-1">
                            @include('Frontend.components.user-badge', ['user' => $reply->user])
                        </div>
                    </div>

                    <span class="ms-5 text-muted">
                        - {{ $reply->created_at->locale('vi')->diffForHumans() }}
                    </span>

                    @php $isReplyVip = $reply->user->hasRole('VIP SIÊU VIỆT'); @endphp

                    <p class="break-words mt-2 {{ $isReplyVip ? 'vip-sieu-viet-content' : '' }}">
                        {!! \App\Helpers\Helper::parseLinks($reply->content) !!}
                    </p>

                    @if ($authUser && ($reply->user_id === $authUser->id || $authUser->hasRole(['Admin','Mod'])))
                        <div class="d-flex">
                            <button onclick="confirmDelete({{ $reply->id }})"
                                    class="btn btn-sm btn-outline-danger">
                                Xóa
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach

        {{-- Show more replies --}}
        @if ($replyCount > 5)
            <div class="text-center mt-2">
                <small class="text-muted">Còn {{ $replyCount - 5 }} phản hồi khác...</small>
            </div>
        @endif
    @endif
</div>
