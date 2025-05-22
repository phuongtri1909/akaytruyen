<?php
// app/Http/Middleware/LogIpRequests.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LogIpRequests
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $route = $request->path();
        $method = $request->method();
        $userAgent = $request->header('User-Agent');
        $timestamp = now()->format('Y-m-d H:i:s');
        
        // Lấy số lượng kết nối database hiện tại
        $dbConnectionCount = 0;
        try {
            // Sử dụng truy vấn trực tiếp để lấy số lượng kết nối
            if (config('database.default') === 'mysql') {
                $dbConnectionCount = DB::select('SELECT COUNT(*) as count FROM information_schema.processlist')[0]->count;
            } elseif (config('database.default') === 'pgsql') {
                $dbConnectionCount = DB::select('SELECT COUNT(*) as count FROM pg_stat_activity')[0]->count;
            }
        } catch (\Exception $e) {
            // Bỏ qua lỗi nếu có
        }
        
        // Log thông tin truy cập
        Log::channel('iplog')->info("[{$timestamp}] IP: {$ip} | Route: {$route} | Method: {$method} | User-Agent: {$userAgent} | DB Connections: {$dbConnectionCount}");
        
        return $next($request);
    }
}
