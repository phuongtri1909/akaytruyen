<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;


class User extends Authenticatable
{
    use HasFactory, HasRoles, HasApiTokens, Notifiable;

    const ROLE_ADMIN       = 'Admin';
    const ROLE_ADMIN_BRAND = 'Admin Brand';
    const ROLE_SEO         = 'SEO';
    const ROLE_CONTENT     = 'Content';

    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;

    const STATUS_TEXT = [
        self::STATUS_ACTIVE   => 'Hoạt động',
        self::STATUS_INACTIVE => 'Ngừng hoạt động',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'avatar',
        'role', 'status', 'active', 'key_active',
        'key_reset_password', 'reset_password_at', 'ip_address',
        'last_login_time', 'email_verified_at', 'remember_token',
        'created_at', 'ban_login', 'ban_comment', 'ban_rate', 'ban_read',
        'ip_address', 'rating','ban_login',
        'ban_comment',
        'ban_rate',
        'ban_read',
        'created_by',
        'google_id',
        'donate_amount'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'last_login_time'   => 'datetime',
    ];

    // public function domains()
    // {
    //     return $this->belongsToMany(Domain::class, 'user_domain')->withPivot('user_id', 'domain_id');
    // }
    public function savedChapters()
{
    return $this->belongsToMany(Chapter::class, 'saved_chapters', 'user_id', 'chapter_id')->withTimestamps();
}


public function isBanLogin(): bool
{
    return $this->ban_login;
}

public function isBanComment(): bool
{
    return $this->ban_comment;
}

public function isBanRate(): bool
{
    return $this->ban_rate;
}

public function isBanRead(): bool
{
    return $this->ban_read;
}


public function banned_ips()
{
    return $this->hasMany(Banned_ip::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function routeNotificationForDatabase()
{
    return $this->notifications(); 
}

public function notifications()
{
    return $this->morphMany(DatabaseNotification::class, 'notifiable')
                ->newQuery()
                ->from('user_notifications');
}

}
