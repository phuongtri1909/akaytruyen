<?php

namespace App\Repositories\Chapter;

use App\Models\Chapter;
use App\Repositories\BaseRepository;
use App\Helpers\CacheHelper;
use Illuminate\Support\Facades\DB;

class ChapterRepository extends BaseRepository implements ChapterRepositoryInterface
{
    // Cache time: 60 phút
    const CACHE_TIME = 3600;

    /**
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return new Chapter();
    }

    public function getChapterLast($storyIds)
    {
        $chapter = Chapter::first();
        if (empty($storyIds)) {
            return collect(); // Trả về collection rỗng nếu không có story_id nào
        }
        
        $cacheKey = CacheHelper::makeKey('chapter_last', [$storyIds]);
        
        return CacheHelper::remember($cacheKey, function() use ($chapter, $storyIds) {
            if ($chapter) {
                return DB::table('chapters as c')
                    ->join(DB::raw('(SELECT MAX(id) AS max_id FROM chapters WHERE story_id IN (' . implode(',', $storyIds) . ') GROUP BY story_id) latest_chapters'), 'c.id', '=', 'latest_chapters.max_id')
                    ->select('c.*')
                    ->get();
            } else {
                return null;
            }
        }, self::CACHE_TIME);
    }

    public function getChaptersByStoryId($storyId, $isOldFirst = false)
    {
        if (empty($storyId)) {
            return collect(); // Trả về collection rỗng nếu không có story_id
        }
        
        $cacheKey = CacheHelper::makeKey('chapters_by_story', [$storyId, $isOldFirst]);
        
        return CacheHelper::remember($cacheKey, function() use ($storyId, $isOldFirst) {
            return $this->getModel()
                ->query()
                ->where('story_id', $storyId)
                ->orderBy('chapter', $isOldFirst ? 'ASC' : 'DESC') // Nếu isOldFirst = true thì sắp xếp ASC (từ nhỏ -> lớn)
                ->paginate(50);
        }, self::CACHE_TIME);
    }
    
    public function getListChapterByStoryId($storyId)
    {
        if (empty($storyId)) {
            return collect();
        }
        
        $cacheKey = CacheHelper::makeKey('chapter_list_by_story', [$storyId]);
        
        return CacheHelper::remember($cacheKey, function() use ($storyId) {
            return $this->getModel()
                ->query()
                ->where('story_id', '=', $storyId)
                ->select(['id', 'name', 'slug', 'chapter'])
                ->get();
        }, self::CACHE_TIME);
    }

    public function getChaptersNewByStoryId($storyId)
    {
        if (empty($storyId)) {
            return collect();
        }
        
        $cacheKey = CacheHelper::makeKey('chapters_new_by_story', [$storyId]);
        
        return CacheHelper::remember($cacheKey, function() use ($storyId) {
            return $this->getModel()
                ->query()
                ->where('story_id', '=', $storyId)
                ->where('is_new', '=', Chapter::IS_NEW)
                ->orderBy('chapter', 'desc')
                ->select('id', 'name', 'slug')
                ->get();
        }, self::CACHE_TIME);
    }

    public function getChapterSingle($storyId, $slug)
    {
        if (empty($storyId) || empty($slug)) {
            return null;
        }
        
        $cacheKey = CacheHelper::makeKey('chapter_single', [$storyId, $slug]);
        
        return CacheHelper::remember($cacheKey, function() use ($storyId, $slug) {
            return $this->getModel()
                ->query()
                ->where('story_id', '=', $storyId)
                ->where('slug', '=', $slug)
                ->first();
        }, self::CACHE_TIME);
    }

    public function getChapterLastSingle($storyId)
    {
        if (empty($storyId)) {
            return null;
        }
        
        $cacheKey = CacheHelper::makeKey('chapter_last_single', [$storyId]);
        
        return CacheHelper::remember($cacheKey, function() use ($storyId) {
            return $this->getModel()
                ->query()
                ->where('story_id', '=', $storyId)
                ->orderBy('id', 'DESC')
                ->first();
        }, self::CACHE_TIME);
    }
    
    public function findBySlug(string $slug)
    {
        $cacheKey = CacheHelper::makeKey('chapter_by_slug', [$slug]);
        
        return CacheHelper::remember($cacheKey, function() use ($slug) {
            return $this->getModel()->where('slug', $slug)->first();
        }, self::CACHE_TIME);
    }

    public function findBySlugExcept(string $slug, int $id)
    {
        $cacheKey = CacheHelper::makeKey('chapter_by_slug_except', [$slug, $id]);
        
        return CacheHelper::remember($cacheKey, function() use ($slug, $id) {
            return $this->getModel()->where('slug', $slug)->where('id', '!=', $id)->first();
        }, self::CACHE_TIME);
    }
    
    /**
     * @param array $attributes
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        $result = parent::create($attributes);
        
        if (isset($attributes['story_id'])) {
            CacheHelper::forgetChapterCache(null, $attributes['story_id']);
            CacheHelper::forgetStoryCache($attributes['story_id']);
        }
        
        return $result;
    }
    
    /**
     * @param int $id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $id, array $attributes = [])
    {
        $chapter = $this->find($id);
        $result = parent::update($id, $attributes);
        
        if ($chapter) {
            CacheHelper::forgetChapterCache($id, $chapter->story_id);
            CacheHelper::forgetStoryCache($chapter->story_id);
        }
        
        return $result;
    }
    
    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $chapter = $this->find($id);
        $result = parent::delete($id);
        
        if ($chapter) {
            CacheHelper::forgetChapterCache($id, $chapter->story_id);
            CacheHelper::forgetStoryCache($chapter->story_id);
        }
        
        return $result;
    }
}
