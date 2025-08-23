<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'stars',
        'controller_name',
        'count',
        'approved',
    ];

    const IS_APPROVED = 1;

    public function story() {
        return $this->belongsTo(Story::class, 'story_id', 'id');
    }
}
