<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Chapter;
use App\Models\LiveComment;
use App\Models\Story;
use App\Models\User;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Chapter\ChapterRepositoryInterface;
use App\Repositories\Story\StoryRepositoryInterface;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\SEOTools;

use App\Models\Donation;

class HomeController extends Controller
{
    public function __construct(
        protected StoryRepositoryInterface $storyRepository,
        protected ChapterRepositoryInterface $chapterRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index(Request $request)
    {
        SEOTools::setTitle('Trang chủ - Akay Truyện');
        SEOTools::setDescription('Đọc truyện miễn phí với giao diện đẹp, cập nhật nhanh nhất.');
        SEOTools::setCanonical(url()->current());

        OpenGraph::setTitle('Trang chủ - Akay Truyện');
        OpenGraph::setDescription('Đọc truyện miễn phí với giao diện đẹp, cập nhật nhanh nhất.');
        OpenGraph::setUrl(url()->current());
        OpenGraph::addProperty('type', 'website');

        TwitterCard::setTitle('Trang chủ - Akay Truyện');
        TwitterCard::setSite('@AkayTruyen');
        $setting = Helper::getSetting();

        $objectSEO = (object) [
            'name' => $setting ? $setting->title : 'Akay Truyện',
            'description' => $setting ? $setting->description : 'Đọc truyện online, truyện hay. Akay Truyện luôn tổng hợp và cập nhật các chương truyện một cách nhanh nhất.',
            'keywords' => 'doc truyen, doc truyen online, truyen hay, truyen chu',
            'no_index' => $setting ? !$setting->index : env('NO_INDEX'),
            'meta_type' => 'WebPage',
            'url_canonical' => route('home'),
            'image' => asset('assets/frontend/images/logo_text.png'),
            'site_name' => 'Akay Truyện'
        ];

        Helper::setSEO($objectSEO);

        $storiesHot = $this->storyRepository->getStoriesHot(16);
        $storiesNewIds = $this->storyRepository->getStoriesNewIds()->toArray();
        $storiesNew = $this->storyRepository->getStoriesNew($storiesNewIds);
        $chapterLast = $this->chapterRepository->getChapterLast($storiesNewIds) ?? [];

        $storiesFullIds = $this->storyRepository->getStoriesFullIds()->toArray();
        $storiesFull = $this->storyRepository->getStoriesFull($storiesFullIds);
        $chapterLastOffFull = $this->chapterRepository->getChapterLast($storiesFullIds);

        $storiesNew->map(function ($story) use ($chapterLast) {
            foreach ($chapterLast as $chapter) {
                if ($chapter->story_id == $story->id) {
                    return $story->chapter_last = $chapter;
                }
            }
        });

        $storiesFull->map(function ($story) use ($chapterLastOffFull) {
            foreach ($chapterLastOffFull as $chapter) {
                if ($chapter->story_id == $story->id) {
                    return $story->chapter_last = $chapter;
                }
            }
        });


        // Get pinned comments separately
        $pinnedComments = LiveComment::with(['user', 'replies.user', 'reactions'])
            ->whereNull('reply_id')
            ->where('is_pinned', true)
            ->latest()
            ->get();

        // Get regular comments with pagination
        $regularComments = LiveComment::with(['user', 'replies.user', 'reactions'])
            ->whereNull('reply_id')
            ->where('is_pinned', false)  // Explicitly exclude pinned comments
            ->latest()
            ->paginate(10);


        // $categories = Helper::getCategoies();
        $totalStory = Story::query()->count();
        $totalChapter = Chapter::query()->count();
        $totalViews = Chapter::query()->sum('views');
        $totalRating = User::query()->sum('rating');

        $selectedMonth = $request->query('month', Carbon::now()->month);
        $selectedYear = $request->query('year', Carbon::now()->year);

        // Lấy danh sách các tháng có donate
        $months = Donation::selectRaw('MONTH(donated_at) as month, YEAR(donated_at) as year')
            ->groupBy('month', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Lấy danh sách donate theo tháng được chọn
        $usersDonate = User::where('donate_amount', '>', 0)
            ->whereMonth('updated_at', $selectedMonth)
            ->whereYear('updated_at', $selectedYear)
            ->selectRaw("CAST(name AS CHAR CHARACTER SET utf8mb4) as name, donate_amount, updated_at")
            ->orderBy('donate_amount', 'desc');

        $guestDonate = Donation::whereMonth('donated_at', $selectedMonth)
            ->whereYear('donated_at', $selectedYear)
            ->selectRaw("CAST(name AS CHAR CHARACTER SET utf8mb4) as name, amount as donate_amount, donated_at as updated_at");

        // Gộp hai danh sách lại và lấy toàn bộ dữ liệu
        $topDonors = $usersDonate->union($guestDonate)
            ->orderByDesc('donate_amount')
            ->get();

        return view('Frontend.home', compact(
            'storiesHot',
            'storiesNew',
            'storiesFull',
            'pinnedComments',
            'regularComments',
            'totalStory',
            'totalChapter',
            'totalViews',
            'totalRating',
            'topDonors',
            'months',
            'selectedMonth',
            'selectedYear'
        ));
    }

    public function getListStoryHot(Request $request)
    {
        $res = ['success' => false];

        $categoryIdInput = $request->input('category_id');
        // $category = $this->categoryRepository->find($categoryId, ['stories']);

        if ($categoryIdInput === 'all' || intval($categoryIdInput) === 0) {
            $stories = Story::where('status', Story::STATUS_ACTIVE)->get();
            $categoryId = 0; // hoặc gán giá trị phù hợp cho giao diện
        } else {
            $categoryId = intval($categoryIdInput);
            $category = $this->categoryRepository->find($categoryId, ['stories']);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục không tồn tại'
                ]);
            }

            // Nếu lấy theo danh mục thì giới hạn 16 story
            $stories = $category->stories()
                ->where('status', Story::STATUS_ACTIVE)
                ->limit(16)
                ->get();
        }

        $param = [
            'categoryIdSelected' => $categoryId,
            'categories' => Helper::getCategoies(),
            'storiesHot' => $stories
        ];
        $html = view('Frontend.sections.main.stories_hot', $param)->toHtml();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function getListStoryHotRandom(Request $request)
    {
        $res = ['success' => false];

        $stories = $this->storyRepository->getStoriesHotRandom(16);

        $param = [
            'categoryIdSelected' => 0,
            'categories' => Helper::getCategoies(),
            'storiesHot' => $stories
        ];

        $html = view('Frontend.sections.main.stories_hot', $param)->toHtml();

        $res['success'] = true;
        $res['html'] = $html;

        return response()->json($res);
    }

    public function searchStory(Request $request)
    {
        $res = ['success' => false];

        $stories = $this->storyRepository->getStoryWithByKeyWord($request->input('key_word'));

        $res['success'] = true;
        $res['stories'] = $stories;

        return response()->json($res);
    }

    public function mainSearchStory(Request $request)
    {
        // dd($request->get('key_word'));
        $stories = $this->storyRepository->getStoryWithByKeyWord($request->get('key_word'));

        $storiesIds = [];
        if (count($stories) > 0) {
            foreach ($stories as $story) {
                $storiesIds[] = $story->id;
            }
        }

        $chapterLast = [];

        if ($storiesIds) {
            $chapterLast = $this->chapterRepository->getChapterLast($storiesIds);
            $stories->map(function ($story) use ($chapterLast) {
                foreach ($chapterLast as $chapter) {
                    if ($story->id == $chapter->story_id) {
                        return $story->chapter_last = $chapter;
                    }
                }
            });
        }

        $data = [
            'stories' => $stories,
            'keyWord' => $request->get('key_word')
        ];
        return view('Frontend.main_search', $data);
    }
    public function searchChapters(Request $request, $slug)
    {
        try {
            $searchTerm = $request->search;

            // Lấy truyện theo slug
            $story = Story::where('slug', $slug)->firstOrFail();
            $storyId = $story->id;

            // ✅ Chuẩn hóa từ khóa
            $decodedSearchTerm = html_entity_decode($searchTerm, ENT_QUOTES, 'UTF-8');
            $cleanedSearchTerm = $this->cleanWordText($decodedSearchTerm);
            $normalizedSearchTerm = strtolower(trim($cleanedSearchTerm));

            $query = Chapter::where('story_id', $storyId); // Chỉ tìm trong truyện hiện tại

            // Nếu có từ khóa, tiến hành tìm kiếm
            if (!empty($normalizedSearchTerm)) {
                $searchNumber = preg_replace('/[^0-9]/', '', $normalizedSearchTerm);

                $query->where(function ($q) use ($normalizedSearchTerm, $searchNumber) {
                    $q->whereRaw("LOWER(CONVERT(name USING utf8mb4)) LIKE ?", ["%{$normalizedSearchTerm}%"])
                        ->orWhereRaw("LOWER(CONVERT(content USING utf8mb4)) LIKE ?", ["%{$normalizedSearchTerm}%"]);

                    if ($searchNumber !== '') {
                        $q->orWhere('chapter', $searchNumber);
                    }
                });
            }

            // Lấy danh sách chương
            $chapters = $query->orderBy('created_at', 'desc')
                ->orderBy('chapter', 'desc')
                ->get();
            // Sắp xếp và phân trang 50 chương mỗi trang
            $chapters = $query->orderBy('chapter', 'desc')->paginate(50);
            // Trả về kết quả dạng HTML
            return response()->json([
                'html' => view('Frontend.components.search-results', compact('chapters'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    private function cleanWordText($text)
    {
        $search = [
            "\xC2\xAB",
            "\xC2\xBB", // « »
            "\xE2\x80\x98",
            "\xE2\x80\x99", // ‘ ’ (nháy đơn)
            "\xE2\x80\x9C",
            "\xE2\x80\x9D", // “ ” (nháy kép)
            "\xE2\x80\x93",
            "\xE2\x80\x94", // – — (gạch ngang)
            "\xE2\x80\xA6", // … (dấu ba chấm)
        ];

        $replace = [
            "<<",
            ">>",
            "'",
            "'",
            '"',
            '"',
            "-",
            "-",
            "...",
        ];

        // ✅ Xóa khoảng trắng dư thừa
        $text = trim($text);

        // ✅ Giải mã HTML entities
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        // ✅ Thay thế các ký tự đặc biệt của Word
        return str_replace($search, $replace, $text);
    }


    public function storeDonate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
        ]);

        Donation::create([
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with('success', 'Donate thành công!');
    }
    public function destroy($id)
    {
        $donation = Donation::find($id);
        if ($donation) {
            $donation->delete();
            return redirect()->back()->with('success', 'Xóa donate thành công!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy donate!');
    }
}
