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

    public $pinnedComment;
    protected $listeners = ['deleteComment'];
    protected $rules = [
        'content' => 'required|min:1',
    ];

    public function postComment()
    {
        $this->validate();
        Livechat::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
            'parent_id' => $this->parent_id
        ]);
        $this->content = ''; // Reset nội dung sau khi gửi bình luận
    }

    public function deleteComment($id)
    {
        $comment = Livechat::find($id);
    
        if (!$comment) return;
    
        if (
            auth()->id() === $comment->user_id ||
            auth()->user()->hasRole('Admin') ||
            auth()->user()->hasRole('Mod')
        ) {
            $comment->delete();
            session()->flash('delete_success', 'Xóa thành công');
        }
    }
    
    public function react($commentId, $type)
    {
        $reaction = LivechatReaction::where('user_id', Auth::id())->where('comment_id', $commentId)->first();
        if ($reaction) {
            if ($reaction->type === $type) {
                $reaction->delete(); // Bỏ cảm xúc nếu nhấn lần nữa
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
        $comment = Livechat::find($commentId);
    
        if (!$comment) {
            return;
        }
    
        if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Mod')) {
            if ($comment->pinned) {
                // Nếu bình luận đang được ghim, bỏ ghim nó
                $comment->update(['pinned' => false]);
            } else {
                // Đếm số bình luận đang ghim
                $pinnedCount = Livechat::where('pinned', true)->count();
    
                if ($pinnedCount >= 2) {
                    // Nếu đã có 2 bình luận ghim, bỏ ghim bình luận ghim lâu nhất
                    $oldestPinned = Livechat::where('pinned', true)
                        ->orderBy('updated_at', 'asc')
                        ->first();
    
                    if ($oldestPinned) {
                        $oldestPinned->update(['pinned' => false]);
                    }
                }
    
                // Ghim bình luận mới
                $comment->update(['pinned' => true]);
            }
        }
    }
    
    

public function render()
{
    $comments = Livechat::whereNull('parent_id')
        ->with(['user', 'replies' => function ($query) {
            $query->orderBy('created_at', 'asc')->with('user'); // Sắp xếp phản hồi theo thời gian
        }])
        ->orderByDesc('pinned') // Sắp xếp bình luận ghim trước
        ->orderByDesc('created_at') // Sau đó sắp xếp theo thời gian
        ->get();

    return view('Frontend.livewire.comment-section', ['comments' => $comments]);
}

public function parseLinks($text)
{

    // Escape để an toàn XSS
    $text = e($text);

    // Tự động nhận link và thêm thẻ <a>
    $text = preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<a href="$1" target="_blank" class="text-blue-500 underline hover:text-blue-700">$1</a>',
        $text
    );

    // Xuống dòng đúng
    return nl2br($text);
}
    
}

