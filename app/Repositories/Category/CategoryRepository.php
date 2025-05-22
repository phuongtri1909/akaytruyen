<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;
use App\Helpers\CacheHelper;
use Illuminate\Support\Facades\Cache;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    // Cache time: 60 phút
    const CACHE_TIME = 3600;

    /**
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return new Category();
    }

    public function getCategoryBySlug($slug)
    {
        if (empty($slug)) {
            return null;
        }

        $cacheKey = CacheHelper::makeKey('category_slug', [$slug]);
        
        return CacheHelper::remember($cacheKey, function() use ($slug) {
            return $this->getModel()
                ->query()
                ->with(['stories', 'stories.author'])
                ->where('slug', '=', $slug)
                ->first();
        }, self::CACHE_TIME);
    }

    public function getCategories()
    {
        $cacheKey = 'categories_all';
        
        return CacheHelper::remember($cacheKey, function() {
            return $this->getModel()->query()->pluck('name', 'id')->toArray();
        }, self::CACHE_TIME);
    }
    
    /**
     * @param array $attributes
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        $result = parent::create($attributes);
        CacheHelper::forgetCategoryCache();
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
        CacheHelper::forgetCategoryCache($id);
        return $result;
    }
    
    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $result = parent::delete($id);
        CacheHelper::forgetCategoryCache($id);
        return $result;
    }
}
