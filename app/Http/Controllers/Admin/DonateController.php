<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\Donate;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonateController extends Controller
{
    /**
     * Hiển thị form quản lý donate cho truyện
     */
    public function index($storyId)
    {
        $story = Story::with('donates')->findOrFail($storyId);
        $authUser = Auth::user();

        // Kiểm tra quyền: chỉ Admin hoặc tác giả của truyện mới được xem
        if (!$authUser->hasRole('Admin') && !$authUser->hasRole('Content') && $story->author_id !== $authUser->id) {
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        return view('Admin.pages.donates.index', compact('story', 'authUser'));
    }

    /**
     * Lưu donate mới
     */
    public function store(Request $request, $storyId)
    {
        try {
            \Log::info('Donate store called', ['storyId' => $storyId, 'request' => $request->all()]);

            $story = Story::findOrFail($storyId);
            $authUser = Auth::user();

            // Kiểm tra quyền
            if (!$authUser->hasRole('Admin') && !$authUser->hasRole('Content') && $story->author_id !== $authUser->id) {
                return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện hành động này']);
            }

            $request->validate([
                'bank_name' => 'required|string|max:255',
                'donate_info' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $data = [
                'story_id' => $storyId,
                'bank_name' => $request->bank_name,
                'donate_info' => $request->donate_info
            ];

            // Xử lý upload ảnh
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('donates', $imageName, 'public');
                $data['image'] = $path;
            }

            \Log::info('Creating donate with data', $data);
            $donate = Donate::create($data);
            \Log::info('Donate created successfully', ['donate_id' => $donate->id]);

            return response()->json(['success' => true, 'message' => 'Thêm thông tin donate thành công']);
        } catch (\Exception $e) {
            \Log::error('Error in donate store', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật donate
     */
    public function update(Request $request, $donateId)
    {
        $donate = Donate::with('story')->findOrFail($donateId);
        $authUser = Auth::user();

        // Kiểm tra quyền
        if (!$authUser->hasRole('Admin') && !$authUser->hasRole('Content') && $donate->story->author_id !== $authUser->id) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện hành động này']);
        }

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'donate_info' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'bank_name' => $request->bank_name,
            'donate_info' => $request->donate_info
        ];

        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($donate->image && Storage::disk('public')->exists($donate->image)) {
                Storage::disk('public')->delete($donate->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('donates', $imageName, 'public');
            $data['image'] = $path;
        }

        $donate->update($data);

        return response()->json(['success' => true, 'message' => 'Cập nhật thông tin donate thành công']);
    }

    /**
     * Xóa donate
     */
    public function destroy($donateId)
    {
        $donate = Donate::with('story')->findOrFail($donateId);
        $authUser = Auth::user();

        // Kiểm tra quyền
        if (!$authUser->hasRole('Admin') && !$authUser->hasRole('Content') && $donate->story->author_id !== $authUser->id) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện hành động này']);
        }

        // Xóa ảnh nếu có
        if ($donate->image && Storage::disk('public')->exists($donate->image)) {
            Storage::disk('public')->delete($donate->image);
        }

        $donate->delete();

        return response()->json(['success' => true, 'message' => 'Xóa thông tin donate thành công']);
    }
}
