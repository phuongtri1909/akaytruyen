<div class="border p-3 mb-2 rounded bg-light comment-item">
    <div class="d-flex align-items-center">
        @include('Frontend.components.user-avatar', ['user' => $comment->user])

        <div class="ms-2 flex-grow-1">
            @include('Frontend.components.user-badge', ['user' => $comment->user])

            @auth
                @if (auth()->user()->hasRole(['Admin', 'Mod']) && !$comment->parent_id)
                    <button wire:click="pinComment({{ $comment->id }})" class="btn btn-sm me-2">
                        @if ($comment->pinned)
                            <i class="fas fa-thumbtack text-warning" title="Bỏ ghim"></i>
                        @else
                            <i class="fas fa-thumbtack" title="Ghim"></i>
                        @endif
                    </button>
                @endif
            @endauth
        </div>
    </div>

    <span style="margin-left: 50px;" class="text-muted">-
        {{ $comment->created_at->locale('vi')->diffForHumans() }}</span>

    @php
        $isVipSieuViet = $comment->user && $comment->user->hasRole('VIP SIÊU VIỆT');
    @endphp

    <p class="break-words mt-2 {{ $isVipSieuViet ? 'vip-sieu-viet-content' : '' }}">
        {!! app('App\Http\Livewire\CommentSection')->parseLinks($comment->content) !!}
    </p>

    <div class="d-flex">
        @auth
            <button wire:click="$set('parent_id', {{ $comment->id }})" class="btn btn-sm btn-outline-secondary me-2">
                Trả lời
            </button>

            @if (
                $comment->user &&
                    ($comment->user_id == auth()->id() ||
                        auth()->user()->hasRole(['Admin', 'Mod'])))
                <button onclick="confirmDelete({{ $comment->id }})" class="btn btn-sm btn-outline-danger">
                    Xóa
                </button>
            @endif
        @endauth
    </div>

    {{-- Reply form --}}
    @if ($parent_id == $comment->id)
        @if (!auth()->user()->ban_comment)
            <div class="mt-3 p-2 bg-light rounded">
                <textarea wire:model.lazy="content" class="form-control" placeholder="Viết phản hồi..." rows="2" maxlength="1000"></textarea>
                <div class="mt-2">
                    <button wire:click="postComment" class="btn btn-primary btn-sm me-2" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="postComment">Gửi</span>
                        <span wire:loading wire:target="postComment">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                    <button wire:click="$set('parent_id', null)" class="btn btn-secondary btn-sm">Hủy</button>
                </div>
            </div>
        @else
            <p class="text-danger mt-2">Bạn đã bị cấm bình luận.</p>
        @endif
    @endif

    {{-- Replies --}}
    @if ($comment->replies && $comment->replies->count() > 0)
        @foreach ($comment->replies as $reply)
            @if ($reply->user)
                <div class="fb-reply-border mt-3">
                    <div class="d-flex align-items-center">
                        @include('Frontend.components.user-avatar', ['user' => $reply->user])

                        <div class="ms-2 flex-grow-1">
                            @include('Frontend.components.user-badge', ['user' => $reply->user])
                        </div>
                    </div>

                    <span style="margin-left: 50px;" class="text-muted">-
                        {{ $reply->created_at->locale('vi')->diffForHumans() }}</span>

                    @php
                        $isReplyVipSieuViet = $reply->user->hasRole('VIP SIÊU VIỆT');
                    @endphp

                    <p class="break-words mt-2 {{ $isReplyVipSieuViet ? 'vip-sieu-viet-content' : '' }}">
                        {!! app('App\Http\Livewire\CommentSection')->parseLinks($reply->content) !!}
                    </p>

                    @auth
                        @if (
                            $reply->user_id == auth()->id() ||
                                auth()->user()->hasRole(['Admin', 'Mod']))
                            <div class="d-flex">
                                <button onclick="confirmDelete({{ $reply->id }})" class="btn btn-sm btn-outline-danger">
                                    Xóa
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            @endif
        @endforeach

        {{-- Show more replies nếu cần --}}
        @if ($comment->replies()->count() > 5)
            <div class="text-center mt-2">
                <small class="text-muted">Còn {{ $comment->replies()->count() - 5 }} phản hồi khác...</small>
            </div>
        @endif
    @endif
</div>
