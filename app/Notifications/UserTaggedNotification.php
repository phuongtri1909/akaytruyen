<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserTaggedNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $taggedBy;

    public function __construct($comment, $taggedBy)
    {
        $this->comment = $comment;
        $this->taggedBy = $taggedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->taggedBy->name . ' đã nhắc bạn trong một bình luận.',
            'comment_id' => $this->comment->id,
            'url' => route('comments.show', $this->comment->id),
        ];
    }
}
