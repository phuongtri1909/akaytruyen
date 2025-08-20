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
        $story = $this->storyRepository->getStoryBySlug($slug, ['categories', 'author', 'author.stories', 'star']);
        if (!$story) {
            abort(404, 'Truyện không tồn tại');
        }
        $chapters = $this->chapterRepository->getChaptersByStoryId($story->id);
        $chaptersNew = $this->chapterRepository->getChaptersNewByStoryId($story->id);
        // dd($chapters);

        $ratingsDay = $this->ratingRepository->getRatingByType(Rating::TYPE_DAY);
        $arrStoryIdsRatingsDay = $this->getStoryIds(json_decode($ratingsDay->value ?? '', true)) ?? [];
        $storiesDay = $this->ratingRepository->getStories($arrStoryIdsRatingsDay) ?? [];

        $ratingsMonth = $this->ratingRepository->getRatingByType(Rating::TYPE_MONTH);
        $arrStoryIdsRatingsMonth = $this->getStoryIds(json_decode($ratingsMonth->value ?? '', true)) ?? [];
        $storiesMonth = $this->ratingRepository->getStories($arrStoryIdsRatingsMonth) ?? [];

        $ratingsAllTime = $this->ratingRepository->getRatingByType(Rating::TYPE_ALL_TIME);
        $arrStoryIdsRatingsAllTime = $this->getStoryIds(json_decode($ratingsAllTime->value ?? '', true)) ?? [];
        $storiesAllTime = $this->ratingRepository->getStories($arrStoryIdsRatingsAllTime) ?? [];

        $stats = [
            'total' => User::where('active', 'active')->count(),
            'admin' => User::where('active', 'active')->where('role', 'admin')->count(),
            'mod' => User::where('active', 'active')->where('role', 'mod')->count(),
            'user' => User::where('active', 'active')->where('role', 'user')->count(),
            'vip' => User::where('active', 'active')->where('role', 'vip')->count(),
        ];
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

        $story = Story::where('slug', $slug)->with(['author', 'categories', 'chapters', 'ratings'])->firstOrFail();
        $storyViews = Chapter::where('story_id', $story->id)->sum('views');

        $totalChapters = \App\Models\Chapter::where('story_id', $story->id)->count();
        $totalViews = \App\Models\Chapter::where('story_id', $story->id)->sum('views');
        $averageRating = \App\Models\Rating::where('story_id', $story->id)->avg('score') ?? 0;
        $ratingCount = \App\Models\Rating::where('story_id', $story->id)->count();

        // dd($totalChapters, $totalViews, $averageRating, $ratingCount);

        $isOldFirst = filter_var($request->old_first, FILTER_VALIDATE_BOOLEAN);
        $orderDirection = $isOldFirst ? 'asc' : 'desc';
        Helper::setSEO($objectSEO);

        $maxNumber = Chapter::where('story_id', $story->id)->max('chapter') ?? 0;
    $minNumber = Chapter::where('story_id', $story->id)->min('chapter') ?? 0;
    $ranges = [];

    // Chia chương thành các đoạn 100 chương
    $totalChunks = ceil(($maxNumber - $minNumber + 1) / 50);

    for ($i = 0; $i < $totalChunks; $i++) {
        $start = $maxNumber - ($i * 50);
        $end = max($start - 49, $minNumber);
        $ranges[] = ["start" => $end, "end" => $start];
    }

    // Kiểm tra nếu cần đảo ngược thứ tự chương
    $isOldFirst = $request->input('old_first', 0);
    if ($isOldFirst) {
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
