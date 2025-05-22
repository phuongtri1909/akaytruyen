<?php

namespace App\Repositories\Story;

use App\Models\Story;
use App\Repositories\BaseRepository;
use App\Helpers\CacheHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoryRepository extends BaseRepository implements StoryRepositoryInterface
{
    // Cache time: 60 phút
    const CACHE_TIME = 3600;

    /**
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return new Story();
    }

    public function getStoriesActive()
    {
        $cacheKey = 'stories_active';
        
        return CacheHelper::remember($cacheKey, function() {
            return $this->getModel()::query()->where('status', '=', Story::STATUS_ACTIVE)->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesHot($limit)
    {
        $cacheKey = CacheHelper::makeKey('stories_hot', [$limit]);
        
        return CacheHelper::remember($cacheKey, function() use ($limit) {
            return $this->getModel()::query()
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->where('is_hot', '=', Story::IS_HOT)
                ->limit($limit)
                ->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesNewOld()
    {
        $cacheKey = 'stories_new_old';
        
        return CacheHelper::remember($cacheKey, function() {
            return DB::table('stories')
                ->where('stories.status', '=', Story::STATUS_ACTIVE)
                ->select('stories.*', 'categories.name as category_name')
                ->join(DB::raw('(SELECT story_id, MAX(id) as max_id FROM chapters GROUP BY story_id) as latestChapters'), function ($join) {
                    $join->on('latestChapters.story_id', '=', 'stories.id');
                })
                ->join('chapters', function ($join) {
                    $join->on('latestChapters.max_id', '=', 'chapters.id');
                })
                ->join('categorie_storie', 'stories.id', '=', 'categorie_storie.storie_id')
                ->join('categories', 'categorie_storie.categorie_id', '=', 'categories.id')
                ->addSelect(DB::raw('MAX(chapters.id) as max_chapter_id'), 'chapters.name as chapter_last_name')
                ->groupBy('stories.id', 'category_name')
                ->orderBy('stories.id', 'desc')
                ->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesNew($ids)
    {
        $cacheKey = CacheHelper::makeKey('stories_new', [$ids]);
        
        return CacheHelper::remember($cacheKey, function() use ($ids) {
            $now = Carbon::now()->toDateTimeString();
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->subHours(24))->toDateTimeString();

            return $this->getModel()
                ->query()
                ->with(['categories'])
                ->whereIn('id', $ids)
                ->where('is_new', '=', Story::IS_NEW)
                ->limit(20)
                ->orderBy('updated_at', 'desc')
                ->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesNewIds()
    {
        $cacheKey = 'stories_new_ids';
        
        return CacheHelper::remember($cacheKey, function() {
            return $this->getModel()
                ->query()
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->where('is_new', '=', Story::IS_NEW)
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->pluck('id');
        }, self::CACHE_TIME);
    }

    public function getStoriesFull($ids)
    {
        $cacheKey = CacheHelper::makeKey('stories_full', [$ids]);
        
        return CacheHelper::remember($cacheKey, function() use ($ids) {
            return $this->getModel()
                ->where('is_full', '=', Story::FULL)
                ->whereIn('id', $ids)
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesFullIds()
    {
        $cacheKey = 'stories_full_ids';
        
        return CacheHelper::remember($cacheKey, function() {
            return $this->getModel()
                ->query()
                ->where('is_full', '=', Story::FULL)
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->pluck('id');
        }, self::CACHE_TIME);
    }

    public function getStoryBySlug($slug, $with = [])
    {
        if (empty($slug)) {
            return null;
        }
        
        $cacheKey = CacheHelper::makeKey('story_by_slug', [$slug, serialize($with)]);
        
        return CacheHelper::remember($cacheKey, function() use ($slug, $with) {
            return $this->getModel()
                ->query()
                ->with($with)
                ->where('slug', '=', $slug)
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->first();
        }, self::CACHE_TIME);
    }

    public function getStoriesHotRandom($limit)
    {
        $cacheKey = CacheHelper::makeKey('stories_hot_random', [$limit]);
        
        return CacheHelper::remember($cacheKey, function() use ($limit) {
            return $this->getModel()
                ->query()
                ->inRandomOrder()
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->where('is_hot', '=', Story::IS_HOT)
                ->limit($limit)
                ->get();
        }, 600); // 10 phút
    }

    public function getStoryWithByKeyWord($keyWord)
    {
        $cacheKey = CacheHelper::makeKey('story_by_keyword', [$keyWord]);
        
        return CacheHelper::remember($cacheKey, function() use ($keyWord) {
            return $this->getModel()
                ->query()
                ->with(['author'])
                ->where('name', 'LIKE', '%' . $keyWord . '%')
                ->get();
        }, self::CACHE_TIME);
    }

    public function getStoriesWithChaptersCount($value)
    {
        // Kiểm tra giá trị null và set giá trị mặc định
        if (is_null($value) || !is_array($value)) {
            // Giá trị mặc định: từ 0 đến vô hạn
            $minChapters = 0;
            $maxChapters = PHP_INT_MAX;
        } else {
            $minChapters = $value[0] ?? 0;
            $maxChapters = $value[1] ?? PHP_INT_MAX;
        }
        
        $cacheKey = CacheHelper::makeKey('stories_with_chapters_count', [$minChapters, $maxChapters]);
        
        return CacheHelper::remember($cacheKey, function() use ($minChapters, $maxChapters) {
            return $this->getModel()->query()
                ->where('status', '=', Story::STATUS_ACTIVE)
                ->withCount('chapters')
                ->has('chapters', '>=', $minChapters)
                ->has('chapters', '<=', $maxChapters)
                ->get();
        }, self::CACHE_TIME);
    }
    
    /**
     * @param array $attributes
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        $result = parent::create($attributes);
        CacheHelper::forgetStoryCache();
        return $result;
    }
    
    /**
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $id, array $attributes = [])
    {
        $result = parent::update($id, $attributes);
        CacheHelper::forgetStoryCache($id);
        return $result;
    }
    
    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        CacheHelper::forgetStoryCache($id);
        return $result;
    }
}
