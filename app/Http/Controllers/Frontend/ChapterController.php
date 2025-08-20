<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Models\Chapter;
use App\Models\Comment;
use App\Repositories\Chapter\ChapterRepositoryInterface;
use App\Repositories\Story\StoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function __construct(
        protected StoryRepositoryInterface $storyRepository,
        protected ChapterRepositoryInterface $chapterRepository
    ) {
    }

public function index(Request $request, $slugStory, $slugChapter)
{
    $allowedRoles = ['Admin', 'vip', 'Mod', 'SEO', 'Content', 'VIP PRO', 'VIP PRO MAX', 'VIP SIÊU VIỆT'];

    $story = $this->storyRepository->getStoryBySlug($slugStory);

    if (!$story) {
        return abort(404);
    }

    $chapter = $this->chapterRepository->getChapterSingle($story->id, $slugChapter);
    $chapterLast = $this->chapterRepository->getChapterLastSingle($story->id);

    if (!$chapter) {
        return abort(404, 'Không tồn tại chương truyện này!');
    }


    // Xử lý chương kế - trước
    $chapterInt = $chapter->chapter;
    $chapterBefore = $chapterInt > 1
        ? Chapter::where('story_id', $story->id)->where('chapter', $chapterInt - 1)->first()
        : null;
    $chapterAfter = Chapter::where('story_id', $story->id)->where('chapter', $chapterInt + 1)->first();

    // SEO + Lượt xem
    $setting = Helper::getSetting();
    $objectSEO = (object) [
        'name' => $chapter->name,
        'description' => Str::limit($story->desc, 30),
        'keywords' => 'doc truyen, doc truyen online, truyen hay, truyen chu',
        'no_index' => $setting ? !$setting->index : env('NO_INDEX'),
        'meta_type' => 'Book',
        'url_canonical' => url()->current(),
        'image' => asset($story->image),
        'site_name' => $chapter->name,
    ];
    $objectSEO->article = [
        'author' => $story->author->name,
        'published_time' => $story->created_at->toAtomString(),
    ];
    Helper::setSEO($objectSEO);

    // Tính số từ
    $cleanContent = strip_tags($chapter->content);
    $words = preg_split('/\s+/u', trim($cleanContent));
    $chapter->word_count = count($words);

    // View tracking theo IP
    $ip = $request->ip();
    $sessionKey = "chapter_view_{$chapter->id}_{$ip}";
    if (!session()->has($sessionKey)) {
        $chapter->increment('views');
        session([$sessionKey => true]);
    }

    // Breadcrumb
    $breadcrumbEndpoint = 'Chương ' . $chapter->chapter;

    // Bình luận
    $pinnedComments = Comment::where('chapter_id', $chapter->slug)
        ->with(['user', 'replies.user', 'reactions'])
        ->whereNull('reply_id')
        ->where('is_pinned', true)
        ->latest()
        ->get();

    $regularComments = Comment::where('chapter_id', $chapter->slug)
        ->with(['user', 'replies.user', 'reactions'])
        ->whereNull('reply_id')
        ->where('is_pinned', false)
        ->latest()
        ->get();

    // Xử lý AJAX (nếu có)
    if ($request->ajax()) {
        if ($request->type === 'comments') {
            $showPinned = $request->page == 1;
            return response()->json([
                'html' => view('components.comments-list', [
                    'pinnedComments' => $showPinned ? $pinnedComments : collect([]),
                    'regularComments' => $regularComments
                ])->render(),
                'hasMore' => false
            ]);
        }

        return response()->json([
            'html' => view('components.chapter-items', compact('chapters'))->render()
        ]);
    }

    // Trả về view
    return view('Frontend.chapter', compact(
        'story', 'chapter', 'slugChapter', 'chapterLast', 'breadcrumbEndpoint',
        'chapterBefore', 'chapterAfter', 'pinnedComments', 'regularComments','slugStory'
    ));
}

    public function getChapters(Request $request)
    {
        $res = ['success' => false];

        $listChapter = $this->chapterRepository->getListChapterByStoryId($request->input('story_id'));

        $res['chapters'] = $listChapter;
        $res['success'] = true;

        return response()->json($res);

    }

    public function search(Request $request)
    {
        try {
            $searchTerm = trim($request->input('search'));
            $query = Chapter::query();
    
            // Kiểm tra quyền hiển thị
            if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'mod'])) {
                $query->where('status', 'published');
            }
    
            if ($searchTerm) {
                $searchNumber = preg_replace('/[^0-9]/', '', $searchTerm);
    
                $query->where(function ($q) use ($searchTerm, $searchNumber) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('name', 'like', "%{$searchTerm}%");
    
                    if ($searchNumber !== '') {
                        $q->orWhere('number', $searchNumber);
                    }
                });
            }
    
            $chapters = $query->orderBy('number', 'desc')->get();
    
            // Render lại danh sách chương
            $html = view('components.chapter-items', compact('chapters'))->render();
    
            return response()->json([
                'html' => $html,
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function saveChapter(Request $request)
{
    try {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để lưu chương.']);
        }

        if (!$request->has('chapter_id')) {
            return response()->json(['success' => false, 'message' => 'Thiếu ID chương.']);
        }

        $chapterId = $request->chapter_id;
        $user = auth()->user();

        // Kiểm tra chapter có tồn tại không
        $chapterExists = DB::table('chapters')->where('id', $chapterId)->exists();
        if (!$chapterExists) {
            return response()->json(['success' => false, 'message' => 'Chương không tồn tại.']);
        }

        // Kiểm tra xem chương đã được lưu chưa
        if (!$user->savedChapters()->where('chapter_id', $chapterId)->exists()) {
            $user->savedChapters()->attach($chapterId);
        }

        return response()->json(['success' => true, 'message' => 'Chương đã được lưu!']);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi hệ thống. Vui lòng thử lại sau!',
            'error' => $e->getMessage() // Hiển thị lỗi để debug (chỉ nên bật khi phát triển)
        ], 500);
    }
}

public function removeChapter(Request $request)
{
    $user = auth()->user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện thao tác này.']);
    }

    $chapterId = $request->input('chapter_id');
    $user->savedChapters()->detach($chapterId);

    return response()->json(['success' => true]);
}



    

}