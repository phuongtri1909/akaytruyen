<?php

namespace App\Http\Livewire;

use App\Models\LivechatReaction;
use Livewire\Component;
use App\Models\Comment;
use App\Models\Livechat;
use App\Models\Reaction;
use Illuminate\Support\Facades\Auth;

class CommentSection extends Component
{
    public $content;
    public $parent_id = null;
    public $loadedComments = 10;
    public $hasMoreComments = true;

    protected $listeners = ['deleteComment', 'loadMoreOnScroll'];
    protected $rules = [
        'content' => 'required|min:1',
    ];

    public function mount()
    {
        $this->checkHasMoreComments();
    }

    public function postComment()
    {
        $this->validate();

        if (auth()->user()->ban_comment) {
            session()->flash('error', 'Bạn đã bị cấm bình luận.');
            return;
        }

        Livechat::create([
            'user_id' => Auth::id(),
            'content' => trim($this->content),
            'parent_id' => $this->parent_id
        ]);

        $this->content = '';
        $this->parent_id = null;

        $this->dispatch('commentAdded');
        session()->flash('success', 'Bình luận đã được thêm.');
    }

    public function deleteComment($id)
    {
        $comment = Livechat::find($id);

        if (!$comment) {
            session()->flash('error', 'Bình luận không tồn tại.');
            return;
        }

        if (
            auth()->id() === $comment->user_id ||
            auth()->user()->hasRole('Admin') ||
            auth()->user()->hasRole('Mod')
        ) {
            $comment->replies()->delete();
            $comment->delete();

            $this->dispatch('deleteSuccess');
            session()->flash('delete_success', 'Xóa thành công');
        } else {
            session()->flash('error', 'Bạn không có quyền xóa bình luận này.');
        }
    }

    public function react($commentId, $type)
    {
        if (!auth()->check()) {
            return;
        }

        $reaction = LivechatReaction::where('user_id', Auth::id())
            ->where('comment_id', $commentId)
            ->first();

        if ($reaction) {
            if ($reaction->type === $type) {
                $reaction->delete();
            } else {
                $reaction->update(['type' => $type]);
            }
        } else {
            LivechatReaction::create([
                'user_id' => Auth::id(),
                'comment_id' => $commentId,
                'type' => $type
            ]);
        }
    }

    public function pinComment($commentId)
    {
        if (!auth()->check() || !auth()->user()->hasRole(['Admin', 'Mod'])) {
            return;
        }

        $comment = Livechat::find($commentId);

        if (!$comment || $comment->parent_id !== null) {
            return;
        }

        if ($comment->pinned) {
            $comment->update(['pinned' => false]);
        } else {
            $pinnedCount = Livechat::where('pinned', true)->count();

            if ($pinnedCount >= 2) {
                $oldestPinned = Livechat::where('pinned', true)
                    ->orderBy('updated_at', 'asc')
                    ->first();

                if ($oldestPinned) {
                    $oldestPinned->update(['pinned' => false]);
                }
            }

            $comment->update(['pinned' => true]);
        }
    }

    public function loadMoreComments()
    {
        $this->loadedComments += 10;
        $this->checkHasMoreComments();
    }

    public function loadMoreOnScroll()
    {
        if ($this->hasMoreComments) {
            $this->loadMoreComments();
        }
    }

    private function checkHasMoreComments()
    {
        $totalComments = Livechat::whereNull('parent_id')->count();
        $this->hasMoreComments = $this->loadedComments < $totalComments;
    }

    public function render()
    {
        $comments = Livechat::whereNull('parent_id')
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'avatar', 'ban_comment');
                },
                'user.roles:id,name',
                'replies' => function ($query) {
                    $query->orderBy('created_at', 'asc')
                        ->limit(5)
                        ->with([
                            'user' => function ($subQuery) {
                                $subQuery->select('id', 'name', 'email', 'avatar', 'ban_comment');
                            },
                            'user.roles:id,name'
                        ]);
                }
            ])
            ->orderByDesc('pinned')
            ->orderByDesc('created_at')
            ->limit($this->loadedComments)
            ->get();

        return view('Frontend.livewire.comment-section', [
            'comments' => $comments,
            'hasMoreComments' => $this->hasMoreComments
        ]);
    }

    public function parseLinks($text)
    {
        if (empty($text)) {
            return '';
        }

        $text = e($text);

        $text = preg_replace(
            '/(https?:\/\/[^\s]+)/',
            '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline hover:text-blue-700">$1</a>',
            $text
        );

        $emojiPattern = '/[\x{1F000}-\x{1FFFF}|\x{2600}-\x{27BF}|\x{1F900}-\x{1F9FF}|\x{2B50}|\x{2705}]/u';
        $text = preg_replace_callback($emojiPattern, function ($matches) {
            return '<span class="emoji">' . $matches[0] . '</span>';
        }, $text);

        $result = '<span class="text">' . $text . '</span>';

        return nl2br($result);
    }
}
