<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    // Thời gian cache mặc định: 30 phút
    const DEFAULT_CACHE_TIME = 1800;
    
    /**
     * Tạo key cache từ tên class và tham số
     */
    public static function makeKey($prefix, $params = []): string
    {
        $key = $prefix;
        
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        
        return $key;
    }
    
    /**
     * Lấy dữ liệu từ cache hoặc chạy callback nếu cache không tồn tại
     */
    public static function remember($key, $callback, $time = self::DEFAULT_CACHE_TIME)
    {
        return Cache::remember($key, $time, $callback);
    }
    
    /**
     * Xóa cache theo key hoặc prefix
     */
    public static function forget($key): void
    {
        Cache::forget($key);
    }
    
    /**
     * Xóa cache theo prefix
     */
    public static function forgetByPrefix($prefix): void
    {
        $keys = Cache::getStore()->many(Cache::get('_cache_keys_' . $prefix) ?? []);
        
        foreach ($keys as $key => $value) {
            if ($key && str_starts_with($key, $prefix)) {
                Cache::forget($key);
            }
        }
        
        Cache::forget('_cache_keys_' . $prefix);
    }
    
    /**
     * Xóa tất cả cache liên quan đến story
     */
    public static function forgetStoryCache($storyId = null): void
    {
        if ($storyId) {
            self::forget('story_' . $storyId);
            self::forget('chapters_' . $storyId);
        } else {
            self::forgetByPrefix('story_');
            self::forgetByPrefix('chapters_');
        }
        
        // Xóa cache danh sách truyện
        self::forgetByPrefix('stories_');
        self::forgetByPrefix('categories_');
    }
    
    /**
     * Xóa tất cả cache liên quan đến category
     */
    public static function forgetCategoryCache($categoryId = null): void
    {
        if ($categoryId) {
            self::forget('category_' . $categoryId);
        } else {
            self::forgetByPrefix('category_');
        }
        
        // Xóa cache liên quan
        self::forgetByPrefix('categories_');
    }
    
    /**
     * Xóa tất cả cache liên quan đến chapter
     */
    public static function forgetChapterCache($chapterId = null, $storyId = null): void
    {
        if ($chapterId) {
            self::forget('chapter_' . $chapterId);
        }
        
        if ($storyId) {
            self::forget('chapters_' . $storyId);
        } else {
            self::forgetByPrefix('chapters_');
        }
    }
} 