<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Story extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const FULL = 1;
    const IS_NEW = 1;
    const IS_HOT = 1;

    protected $fillable = [
        'slug',
        'image',
        'name',
        'desc',
        'author_id',
        'status',
        'is_full',
        'is_new',
        'is_hot',
        'views'
    ];

    protected $table = 'stories';

    /**
     * Quan hệ nhiều-nhiều với bảng categories thông qua bảng trung gian categorie_storie
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categorie_storie', 'storie_id', 'categorie_id')
            ->withTimestamps();
    }

    /**
     * Quan hệ một-nhiều với bảng authors
     */
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Quan hệ một-nhiều với bảng chapters
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'story_id');
    }

    /**
     * Latest chapter relation for eager loading the newest chapter per story.
     */
    public function latestChapter()
    {
        return $this->hasOne(Chapter::class, 'story_id')->latestOfMany();
    }

    /**
     * Accessor to keep backward-compatibility for `$story->chapter_last` in views.
     * Returns the eager-loaded latestChapter relation if available.
     */
    public function getChapterLastAttribute()
    {
        return $this->getRelationValue('latestChapter');
    }

    /**
     * Lấy chương mới nhất
     */
    public function getLatestChapter()
    {
        return $this->chapters()->latest('id')->first();
    }

    /**
     * Quan hệ một-một với bảng stars
     */
    public function star()
    {
        return $this->hasOne(Star::class, 'story_id');
    }

    /**
     * Quan hệ một-nhiều với bảng ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'story_id');
    }

    /**
     * Quan hệ một-nhiều với bảng donates
     */
    public function donates()
    {
        return $this->hasMany(Donate::class, 'story_id');
    }

    /**
     * Quan hệ một-nhiều với bảng donations
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'story_id');
    }
}
