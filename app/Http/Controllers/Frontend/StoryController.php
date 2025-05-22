<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Helpers\CacheHelper;
use App\Models\Rating;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Chapter\ChapterRepositoryInterface;
use App\Repositories\Rating\RatingRepositoryInterface;
use App\Repositories\Story\StoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StoryController extends Controller
{
    public function __construct(
        protected StoryRepositoryInterface $storyRepository,
        protected ChapterRepositoryInterface $chapterRepository,
        protected AuthorRepositoryInterface $authorRepository,
        protected RatingRepositoryInterface $ratingRepository
    ) {
    }

    public function index(Request $request, $slug)
    {
        $story = $this->storyRepository->getStoryBySlug($slug, ['categories', 'author', 'author.stories', 'star']);
        if (!$story) {
            abort(404, 'Truyện không tồn tại');
        }
        
        $chapters = $this->chapterRepository->getChaptersByStoryId($story->id);
        $chaptersNew = $this->chapterRepository->getChaptersNewByStoryId($story->id);

        // Thống kê xếp hạng - cache data
        $cacheKey = 'ratings_stats';
        $ratingsStats = CacheHelper::remember($cacheKey, function() {
            $ratingsDay = $this->ratingRepository->getRatingByType(Rating::TYPE_DAY);
            $arrStoryIdsRatingsDay = $this->getStoryIds(json_decode($ratingsDay->value ?? '', true)) ?? [];
            $storiesDay = $this->ratingRepository->getStories($arrStoryIdsRatingsDay) ?? [];
    
            $ratingsMonth = $this->ratingRepository->getRatingByType(Rating::TYPE_MONTH);
            $arrStoryIdsRatingsMonth = $this->getStoryIds(json_decode($ratingsMonth->value ?? '', true)) ?? [];
            $storiesMonth = $this->ratingRepository->getStories($arrStoryIdsRatingsMonth) ?? [];
    
            $ratingsAllTime = $this->ratingRepository->getRatingByType(Rating::TYPE_ALL_TIME);
            $arrStoryIdsRatingsAllTime = $this->getStoryIds(json_decode($ratingsAllTime->value ?? '', true)) ?? [];
            $storiesAllTime = $this->ratingRepository->getStories($arrStoryIdsRatingsAllTime) ?? [];
            
            return [
                'ratingsDay' => $ratingsDay,
                'ratingsMonth' => $ratingsMonth,
                'ratingsAllTime' => $ratingsAllTime,
                'storiesDay' => $storiesDay,
                'storiesMonth' => $storiesMonth,
                'storiesAllTime' => $storiesAllTime
            ];
        }, 3600); // Cache 1 giờ
        
        extract($ratingsStats);

        // Thống kê User - cache data
        $cacheKey = 'user_stats';
        $stats = CacheHelper::remember($cacheKey, function() {
            return [
                'total' => User::where('active', 'active')->count(),
                'admin' => User::where('active', 'active')->where('role', 'admin')->count(),
                'mod' => User::where('active', 'active')->where('role', 'mod')->count(),
                'user' => User::where('active', 'active')->where('role', 'user')->count(),
                'vip' => User::where('active', 'active')->where('role', 'vip')->count(),
            ];
        }, 3600); // Cache 1 giờ
        
        $setting = Helper::getSetting();
        $objectSEO = (object) [
            'name' => $story->name,
            'description' => Str::limit($story->desc, 30),
            'keywords' => str_replace('-', ' ', $story->slug) . ', ' . 'doc truyen, doc truyen online, truyen hay, truyen chu',
            'no_index' => $setting ? !$setting->index : env('NO_INDEX'),
            'meta_type' => 'Book',
            'url_canonical' => url()->current(),
            'image' => asset($story->image),
            'site_name' => $story->name,
        ];

        $objectSEO->article = [
            'author'         => $story->author->name,
            'published_time' => $story->created_at->toAtomString(),
        ];

        Helper::setSEO($objectSEO);
        
        // Thống kê truyện - cache data
        $cacheKey = 'story_stats_' . $story->id;
        $storyStats = CacheHelper::remember($cacheKey, function() use ($story) {
            $storyViews = Chapter::where('story_id', $story->id)->sum('views');
            $totalChapters = Chapter::where('story_id', $story->id)->count();
            $totalViews = Chapter::where('story_id', $story->id)->sum('views');
            $averageRating = Rating::where('story_id', $story->id)->avg('score') ?? 0;
            $ratingCount = Rating::where('story_id', $story->id)->count();
            
            // Lấy phạm vi chapter
            $maxNumber = Chapter::where('story_id', $story->id)->max('chapter') ?? 0;
            $minNumber = Chapter::where('story_id', $story->id)->min('chapter') ?? 0;
            $ranges = [];
        
            // Chia chương thành các đoạn 50 chương
            $totalChunks = ceil(($maxNumber - $minNumber + 1) / 50);
        
            for ($i = 0; $i < $totalChunks; $i++) {
                $start = $maxNumber - ($i * 50);
                $end = max($start - 49, $minNumber);
                $ranges[] = ["start" => $end, "end" => $start];
            }
            
            return [
                'storyViews' => $storyViews,
                'totalChapters' => $totalChapters, 
                'totalViews' => $totalViews,
                'averageRating' => $averageRating,
                'ratingCount' => $ratingCount,
                'ranges' => $ranges,
            ];
        }, 1800); // Cache 30 phút
        
        extract($storyStats);
        
        $isOldFirst = filter_var($request->old_first, FILTER_VALIDATE_BOOLEAN);
        
        // Kiểm tra nếu cần đảo ngược thứ tự chương
        if ($isOldFirst) {
            $ranges = array_reverse($ranges);
        }

        return view('Frontend.story', compact('story', 'ranges', 'chapters', 'chaptersNew', 'slug', 'ratingsDay', 'ratingsMonth', 'ratingsAllTime', 'storiesDay', 'storiesMonth', 'storiesAllTime', 'stats', 'totalChapters', 'totalViews', 'averageRating', 'ratingCount', 'storyViews'));
    }
    
    public function rate(Request $request, Story $story)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $story->ratings()->create([
            'score' => $request->score,
        ]);
        
        // Xóa cache liên quan đến story sau khi rating
        CacheHelper::forgetStoryCache($story->id);
        CacheHelper::forget('story_stats_' . $story->id);

        return back()->with('success', 'Đánh giá của bạn đã được ghi nhận!');
    }

    protected function getStoryIds($ratings)
    {
        $result = [];

        if (!empty($ratings) && is_array($ratings)) {
            foreach ($ratings as $rating) {
                if (isset($rating['id'])) {
                    $result[] = $rating['id'];
                }
            }
        }

        return $result;
    }

    public function followChaptersCount(Request $request)
    {
        // Kiểm tra và thiết lập giá trị mặc định
        $valueRange = $request->input('value');
        $minValue = isset($valueRange) && is_array($valueRange) && isset($valueRange[0]) ? $valueRange[0] : 0;
        $maxValue = isset($valueRange) && is_array($valueRange) && isset($valueRange[1]) ? $valueRange[1] : 999999999;

        // Tạo một mảng có cấu trúc đúng để truyền vào repository
        $range = [$minValue, $maxValue];
        
        // Gọi repository với giá trị đã được kiểm tra (đã được cache)
        $stories = $this->storyRepository->getStoriesWithChaptersCount($range);
        
        if ($maxValue != 999999999) {
            $title = $minValue . ' - ' . $maxValue . ' chương';
        } else {
            $title = 'Trên ' . $minValue . ' chương';
        }

        // Lấy dữ liệu ratings (cache 30 phút)
        $cacheKey = 'ratings_data';
        $ratingsData = CacheHelper::remember($cacheKey, function() {
            $ratingsDay = $this->ratingRepository->getRatingByType(Rating::TYPE_DAY);
            $arrStoryIdsRatingsDay = $this->getStoryIds(json_decode($ratingsDay->value ?? '[]', true));
            $storiesDay = $this->ratingRepository->getStories($arrStoryIdsRatingsDay);
    
            $ratingsMonth = $this->ratingRepository->getRatingByType(Rating::TYPE_MONTH);
            $arrStoryIdsRatingsMonth = $this->getStoryIds(json_decode($ratingsMonth->value ?? '[]', true));
            $storiesMonth = $this->ratingRepository->getStories($arrStoryIdsRatingsMonth);
    
            $ratingsAllTime = $this->ratingRepository->getRatingByType(Rating::TYPE_ALL_TIME);
            $arrStoryIdsRatingsAllTime = $this->getStoryIds(json_decode($ratingsAllTime->value ?? '[]', true));
            $storiesAllTime = $this->ratingRepository->getStories($arrStoryIdsRatingsAllTime);
            
            return [
                'ratingsDay' => $ratingsDay,
                'ratingsMonth' => $ratingsMonth,
                'ratingsAllTime' => $ratingsAllTime,
                'storiesDay' => $storiesDay,
                'storiesMonth' => $storiesMonth,
                'storiesAllTime' => $storiesAllTime
            ];
        }, 1800);
        
        extract($ratingsData);

        return view('Frontend.follow_chapter_count', compact('title', 'stories', 'ratingsDay', 'ratingsMonth', 'ratingsAllTime', 'storiesDay', 'storiesMonth', 'storiesAllTime'));
    }

    /**
     * Phương thức kiểm tra cache
     */
    public function testCache()
    {
        // Tạo key cache test
        $cacheKey = 'test_cache_key';
        
        // Kiểm tra xem cache đã tồn tại chưa
        if (Cache::has($cacheKey)) {
            $cachedValue = Cache::get($cacheKey);
            $cacheTime = Cache::get($cacheKey . '_time');
            
            // Trả về thông tin từ cache
            return response()->json([
                'cache_exists' => true,
                'cached_value' => $cachedValue,
                'cached_time' => $cacheTime,
                'current_time' => now()->format('Y-m-d H:i:s'),
                'driver' => config('cache.default'),
                'message' => 'Đã tìm thấy cache với key: ' . $cacheKey
            ]);
        } else {
            // Tạo giá trị mới và lưu vào cache trong 5 phút
            $newValue = 'Cache được tạo lúc: ' . now()->format('Y-m-d H:i:s');
            Cache::put($cacheKey, $newValue, 300); // 5 phút
            Cache::put($cacheKey . '_time', now()->format('Y-m-d H:i:s'), 300);
            
            // Trả về thông tin mới tạo
            return response()->json([
                'cache_exists' => false,
                'new_value' => $newValue,
                'driver' => config('cache.default'),
                'message' => 'Đã tạo cache mới với key: ' . $cacheKey
            ]);
        }
    }
}
