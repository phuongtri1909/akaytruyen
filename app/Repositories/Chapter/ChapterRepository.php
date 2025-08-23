<?php

namespace App\Repositories\Chapter;

use App\Models\Chapter;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ChapterRepository extends BaseRepository implements ChapterRepositoryInterface
{

    /**
     * @return mixed|\Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return new Chapter();
    }

    public function getChapterLast($storyIds)
    {
        if (empty($storyIds)) {
            return collect(); // Trả về collection rỗng nếu không có story_id nào
        }

        return DB::table('chapters as c')
            ->join(DB::raw('(SELECT story_id, MAX(id) AS max_id FROM chapters WHERE story_id IN (' . implode(',', $storyIds) . ') GROUP BY story_id) latest_chapters'), 'c.id', '=', 'latest_chapters.max_id')
            ->select('c.*')
            ->get();
    }

    public function getChaptersByStoryId($storyId, $isOldFirst = false)
    {
        return $this->getModel()
            ->query()
            ->where('story_id', $storyId)
            ->orderBy('chapter', $isOldFirst ? 'ASC' : 'DESC') // Nếu isOldFirst = true thì sắp xếp ASC (từ nhỏ -> lớn)
            ->paginate(50);
    }



    public function getListChapterByStoryId($storyId)
    {
        return $this->getModel()
            ->query()
            ->where('story_id', '=', $storyId)
            ->select(['id', 'name', 'slug', 'chapter'])
            ->get();
    }

    public function getChaptersNewByStoryId($storyId)
    {
        return Cache::remember("story:chapters_new:{$storyId}", now()->addMinutes(30), function () use ($storyId) {
            return $this->getModel()
                ->query()
                ->where('story_id', '=', $storyId)
                ->where('is_new', '=', Chapter::IS_NEW)
                ->orderBy('chapter', 'desc')
                ->select('id', 'name', 'slug')
                ->get();
        });
    }

    public function getChapterSingle($storyId, $slug)
    {
        return $this->getModel()
            ->query()
            ->where('story_id', '=', $storyId)
            ->where('slug', '=', $slug)
            ->first();
    }

    public function getChapterLastSingle($storyId)
    {
        return $this->getModel()
            ->query()
            ->where('story_id', '=', $storyId)
            ->orderBy('id', 'DESC')
            ->first();
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findBySlugExcept(string $slug, int $id)
    {
        return $this->model->where('slug', $slug)->where('id', '!=', $id)->first();
    }
}
