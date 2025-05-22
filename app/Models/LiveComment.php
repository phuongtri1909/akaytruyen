<?php

namespace App\Models;

use App\Models\LiveReaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'livecomment',
        'user_id',
        'comment',
        'reply_id',
        'level',
        'is_pinned',
        'pinned_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($comment) {
            if ($comment->reply_id) {
                $parentComment = LiveComment::find($comment->reply_id);
                $comment->level = $parentComment ? $parentComment->level + 1 : 0;
            }
        });
    }

    public function replies()
    {
        return $this->hasMany(LiveComment::class, 'reply_id')->where('level', '<', 3);
    }

    public function reactions()
    {
        return $this->hasMany(LiveReaction::class);
    }

    public function likes()
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->reactions()->where('type', 'dislike');
    }

    // Add scope for pinned comments
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true)->orderBy('pinned_at', 'desc');
    }

}
