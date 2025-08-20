<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LivechatReaction extends Model
{
    use HasFactory;
    protected $table = 'livechatreaction'; 
    protected $fillable = ['user_id', 'comment_id', 'type'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Livechat::class, 'comment_id');
    }
}
