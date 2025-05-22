<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:xem_display_data')->only('index');
        $this->middleware('can:sua_display_data')->only('update');
    }

    public function index(Request $request)
    {
        $setting = Setting::query()->first();
        $tinymce_api_key = $setting->tinymce_api_key ?? null;
        return view('Admin.pages.settings.index', [
            'setting' => $setting,
            'tinymce_api_key' => $tinymce_api_key
        ]);
    }

    public function update(Request $request)
{
    // Tìm hoặc tạo mới Setting
    $setting = Setting::query()->first();
    if (!$setting) {
        $setting = new Setting();
    }

    // Chỉ cập nhật các trường cần thiết, không thay đổi các trường không muốn
    $setting->title = $request->input('title', $setting->title);
    $setting->description = $request->input('description', $setting->description);
    $setting->index = $request->input('index', $setting->index);
    $setting->header_script = $request->input('header_script', $setting->header_script);
    $setting->body_script = $request->input('body_script', $setting->body_script);
    $setting->footer_script = $request->input('footer_script', $setting->footer_script);

    // Cập nhật TinyMCE API Key
    $setting->tinymce_api_key = $request->input('tinymce_api_key', $setting->tinymce_api_key);

    // Lưu lại thông tin
    $setting->save();

    return back()->with('success', 'Cập nhật thành công');
}


public function trackTinyMCEUsage(Request $request)
{
    $setting = Setting::query()->first();
    $tinymce_api_key = $setting->tinymce_api_key ?? null;

    if (!$tinymce_api_key) {
        return response()->json(['error' => 'API Key không tồn tại'], 400);
    }

    // Gọi API của TinyMCE
    $url = "https://api.tiny.cloud/accounts/usage?apiKey={$tinymce_api_key}";

    try {
    $response = Http::timeout(5)->get($url);
    
    if ($response->successful()) {
        $data = $response->json();
        return response()->json([
            'tinymce_usage' => $data['editor_loads'] ?? 0,
            'limit' => $data['editor_load_limit'] ?? 1000,
        ]);
    } else {
        // Trả về lỗi chi tiết
        return response()->json([
            'error' => 'Không lấy được dữ liệu từ TinyMCE',
            'status' => $response->status(), // Trạng thái của response
            'body' => $response->body(), // Nội dung phản hồi
        ], 400);
    }
} catch (\Exception $e) {
    // Trả về thông báo lỗi chi tiết
    return response()->json([
        'error' => 'Lỗi kết nối API: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString(), // Thông tin lỗi chi tiết
    ], 500);
}

}
public function getTinyMCEUsage()
{
    $setting = Setting::query()->first();
    $tinymce_api_key = $setting->tinymce_api_key ?? null;

    if (!$tinymce_api_key) {
        return response()->json(['error' => 'API Key không tồn tại'], 400);
    }

    $url = "https://api.tiny.cloud/accounts/usage?apiKey={$tinymce_api_key}";

    try {
        $response = Http::timeout(5)->get($url); 

        if ($response->successful()) {
            $data = $response->json();

            // Kiểm tra dữ liệu trả về
            if (empty($data)) {
                return response()->json(['error' => 'Dữ liệu API trả về rỗng'], 400);
            }

            return response()->json([
                'editor_loads' => $data['editor_loads'] ?? 0,
                'limit' => $data['editor_load_limit'] ?? 1000, 
            ]);
        } else {
            return response()->json([
                'error' => 'Không lấy được dữ liệu từ TinyMCE',
                'status' => $response->status(),
                'message' => $response->body(),
            ], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Lỗi kết nối API: ' . $e->getMessage()], 500);
    }
}

}
