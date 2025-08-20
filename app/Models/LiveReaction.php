<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveReaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'comment_id', 'type'];

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
}
