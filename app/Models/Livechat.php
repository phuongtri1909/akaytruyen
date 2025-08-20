<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livechat extends Model
{
    use HasFactory;

    protected $table = 'livechat';
    protected $fillable = ['user_id', 'content', 'parent_id', 'pinned'];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'pinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Livechat::class, 'parent_id')->latest();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Livechat::class, 'parent_id');
    }

    /**
     * Scope để lấy comments chính (không phải reply)
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope để lấy comments đã ghim
     */
    public function scopePinned($query)
    {
        return $query->where('pinned', true);
    }
}
