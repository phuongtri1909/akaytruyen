<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTagged extends Model
{
    use HasFactory;
    protected $table = 'user_taggeds'; 
    protected $fillable = [
        'user_id',
        'comment_id',
        'tagged_by',
        'chapter_id',
        // thêm các trường khác nếu bạn sử dụng
    ];

    // UserTagged.php
public function comment()
{
    return $this->belongsTo(Comment::class);
}

public function chapter()
{
    return $this->belongsTo(Chapter::class);
}

    
}
