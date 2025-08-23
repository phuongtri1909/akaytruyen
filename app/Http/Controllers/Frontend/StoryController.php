<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Models\Rating;
use App\Models\Chapter;
use App\Models\Story;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Chapter\ChapterRepositoryInterface;
use App\Repositories\Rating\RatingRepositoryInterface;
use App\Repositories\Story\StoryRepositoryInterface;
use Illuminate\Http\Request;
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
        // Use cached optimized story detail
        $story = $this->storyRepository->getCachedStoryDetail($slug);
        if (!$story) {
            abort(404, 'Truyện không tồn tại');
        }

        // Get pagination settings
        $isOldFirst = filter_var($request->old_first, FILTER_VALIDATE_BOOLEAN);
        $page = $request->get('page', 1);

        // Use cached chapters with pagination
        $chapters = $this->storyRepository->getCachedStoryChapters($story->id, $page, $isOldFirst);
        $chaptersNew = $this->chapterRepository->getChaptersNewByStoryId($story->id);

        // Cache ratings data
        $ratingsDay = $this->ratingRepository->getRatingByType(Rating::TYPE_DAY);
        $arrStoryIdsRatingsDay = $this->getStoryIds(json_decode($ratingsDay->value ?? '', true)) ?? [];
        $storiesDay = $this->ratingRepository->getStories($arrStoryIdsRatingsDay) ?? [];

        $ratingsMonth = $this->ratingRepository->getRatingByType(Rating::TYPE_MONTH);
        $arrStoryIdsRatingsMonth = $this->getStoryIds(json_decode($ratingsMonth->value ?? '', true)) ?? [];
        $storiesMonth = $this->ratingRepository->getStories($arrStoryIdsRatingsMonth) ?? [];

        $ratingsAllTime = $this->ratingRepository->getRatingByType(Rating::TYPE_ALL_TIME);
        $arrStoryIdsRatingsAllTime = $this->getStoryIds(json_decode($ratingsAllTime->value ?? '', true)) ?? [];
        $storiesAllTime = $this->ratingRepository->getStories($arrStoryIdsRatingsAllTime) ?? [];

        // Use cached user stats
        $stats = Helper::getCachedUserStats();
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


        $objectSEO->article   = [
            'author'         => $story->author->name,
            'published_time' => $story->created_at->toAtomString(),
        ];

        // Use cached story stats instead of individual queries
        $storyStats = $this->storyRepository->getCachedStoryStats($story->id);
        $totalChapters = $storyStats['total_chapters'];
        $totalViews = $storyStats['total_views'];
        $storyViews = $totalViews; // same as totalViews
        $averageRating = $storyStats['average_rating'];
        $ratingCount = $storyStats['rating_count'];

        $isOldFirst = filter_var($request->old_first, FILTER_VALIDATE_BOOLEAN);
        $orderDirection = $isOldFirst ? 'asc' : 'desc';
        Helper::setSEO($objectSEO);

        // Use cached chapter ranges to avoid min/max queries
        $ranges = $this->storyRepository->getCachedChapterRanges($story->id);

        // Kiểm tra nếu cần đảo ngược thứ tự chương
        $isOldFirst = $request->input('old_first', 0);
        if ($isOldFirst && !empty($ranges)) {
            $ranges = array_reverse($ranges);
        }

        return view('Frontend.story', compact('story','ranges' ,'chapters', 'chaptersNew', 'slug', 'ratingsDay', 'ratingsMonth', 'ratingsAllTime', 'storiesDay', 'storiesMonth', 'storiesAllTime','stats', 'totalChapters', 'totalViews', 'averageRating', 'ratingCount','storyViews'));
    }
    public function rate(Request $request, Story $story)
{
    $request->validate([
        'score' => 'required|integer|min:1|max:5',
    ]);

    $story->ratings()->create([
        'score' => $request->score,
    ]);

    return back()->with('success', 'Đánh giá của bạn đã được ghi nhận!');
}


    protected function getStoryIds($ratings)
    {
        $result = [];

        if ($ratings) {
            foreach ($ratings as $rating) {
                $result[] = $rating['id'];
            }
        }

        return $result;
    }

    public function followChaptersCount(Request $request)
    {
        // dd($request->input());
        $stories = $this->storyRepository->getStoriesWithChaptersCount($request->input('value'));
        if ($request->input('value')[1] != 999999999) {
            $title = $request->input('value')[0] . ' - ' . $request->input('value')[1] . ' chương';
        } else {
            $title = 'Trên ' . $request->input('value')[0] . ' chương';
        }

        $ratingsDay = $this->ratingRepository->getRatingByType(Rating::TYPE_DAY);
        $arrStoryIdsRatingsDay = $this->getStoryIds(json_decode($ratingsDay->value, true));
        $storiesDay = $this->ratingRepository->getStories($arrStoryIdsRatingsDay);

        $ratingsMonth = $this->ratingRepository->getRatingByType(Rating::TYPE_MONTH);
        $arrStoryIdsRatingsMonth = $this->getStoryIds(json_decode($ratingsMonth->value, true));
        $storiesMonth = $this->ratingRepository->getStories($arrStoryIdsRatingsMonth);

        $ratingsAllTime = $this->ratingRepository->getRatingByType(Rating::TYPE_ALL_TIME);
        $arrStoryIdsRatingsAllTime = $this->getStoryIds(json_decode($ratingsAllTime->value, true));
        $storiesAllTime = $this->ratingRepository->getStories($arrStoryIdsRatingsAllTime);

        return view('Frontend.follow_chapter_count', compact('title', 'stories', 'ratingsDay', 'ratingsMonth', 'ratingsAllTime', 'storiesDay', 'storiesMonth', 'storiesAllTime'));
    }
}
