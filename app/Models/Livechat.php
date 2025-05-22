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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Livechat::class, 'parent_id')->latest();
    }
    public function render()
{
    return view('livewire.live-chat', [
        'comments' => Comment::latest()->get(),
    ])->extends('layouts.app')->section('content');
}


}
