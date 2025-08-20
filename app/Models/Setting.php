<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'index', 'header_script', 'body_script', 'footer_script', 'import_db'];

    public static function getValue($key, $default = null)
    {
        $setting = self::query()->first();
        return $setting ? $setting->$key : $default;
    }

}
