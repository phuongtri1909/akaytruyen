<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'index', 'header_script', 'body_script', 'footer_script', 'import_db'];

    public static function getTinymceApiKey()
    {
        return self::where('title', 'tinymce_api_key')->value('header_script') ?? '';
    }

    public static function updateTinymceApiKey($apiKey)
    {
        return self::updateOrCreate(
            ['title' => 'tinymce_api_key'],
            ['header_script' => $apiKey]
        );
    }
    public static function getValue($key, $default = null)
    {
        $setting = self::query()->first();
        return $setting ? $setting->$key : $default;
    }
    
}
