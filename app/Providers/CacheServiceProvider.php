<?php

namespace App\Providers;

use App\Helpers\CacheHelper;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Author;
use App\Models\Rating;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Xử lý cache cho Story model
        Story::created(function ($story) {
            CacheHelper::forgetStoryCache();
        });
        
        Story::updated(function ($story) {
            CacheHelper::forgetStoryCache($story->id);
        });
        
        Story::deleted(function ($story) {
            CacheHelper::forgetStoryCache($story->id);
        });
        
        // Xử lý cache cho Chapter model
        Chapter::created(function ($chapter) {
            CacheHelper::forgetChapterCache(null, $chapter->story_id);
            CacheHelper::forgetStoryCache($chapter->story_id);
        });
        
        Chapter::updated(function ($chapter) {
            CacheHelper::forgetChapterCache($chapter->id, $chapter->story_id);
            CacheHelper::forgetStoryCache($chapter->story_id);
        });
        
        Chapter::deleted(function ($chapter) {
            CacheHelper::forgetChapterCache($chapter->id, $chapter->story_id);
            CacheHelper::forgetStoryCache($chapter->story_id);
        });
        
        // Xử lý cache cho Category model
        Category::created(function ($category) {
            CacheHelper::forgetCategoryCache();
        });
        
        Category::updated(function ($category) {
            CacheHelper::forgetCategoryCache($category->id);
        });
        
        Category::deleted(function ($category) {
            CacheHelper::forgetCategoryCache($category->id);
        });
        
        // Xử lý cache cho Rating model - xóa cache liên quan đến story khi có đánh giá mới
        Rating::created(function ($rating) {
            if ($rating->story_id) {
                CacheHelper::forgetStoryCache($rating->story_id);
                CacheHelper::forget('story_stats_' . $rating->story_id);
                CacheHelper::forget('ratings_stats');
                CacheHelper::forget('ratings_data');
            }
        });
    }
} 