<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Rating;
use App\Models\Status;
use App\Models\Chapter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use FFMpeg\FFProbe\DataMapping\Format;
use Illuminate\Support\Facades\Validator;


class ChapterController extends Controller
{
    public function getTodayChapters()
    {
        try {
            $today = Carbon::today();
            $chapters = Chapter::whereDate('updated_content_at', $today)
                ->where('status', 'published')
                ->select(['id', 'number', 'title', 'slug', 'content', 'updated_content_at'])
                ->orderBy('number')
                ->get();

            return response()->json([
                'today' => $today->locale('vi')->isoFormat('dddd, DD/MM/YYYY'),
                'status' => 'success',
                'data' => $chapters
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting today chapters: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu chương mới'
            ], 500);
        }
    }

    public function getChaptersByRange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|integer|min:1',
            'to' => 'required|integer|min:1|gte:from'
        ], [
            'from.required' => 'Thiếu tham số bắt đầu',
            'from.integer' => 'Tham số bắt đầu phải là số nguyên',
            'from.min' => 'Tham số bắt đầu phải lớn hơn 0',
            'to.required' => 'Thiếu tham số kết thúc',
            'to.integer' => 'Tham số kết thúc phải là số nguyên',
            'to.min' => 'Tham số kết thúc phải lớn hơn 0',
            'to.gte' => 'Tham số kết thúc phải lớn hơn hoặc bằng tham số bắt đầu'
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        try{
            $chapters = Chapter::whereBetween('number', [$request->from, $request->to])
                ->where('status', 'published')
                ->select(['id', 'number', 'title', 'slug', 'content', 'updated_content_at'])
                ->orderBy('number')
                ->paginate(30);

            return response()->json([
                'status' => 'success',
                'data' => $chapters
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting chapters by range: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy dữ liệu chương'
            ], 500);
        }
    }
}
