<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LimitRequestsMiddleware
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $route = $request->path();
        $cacheKey = "ip_requests:$ip";
        
        // Kiểm tra số lượng yêu cầu trong 1 phút
        $requestCount = Cache::get($cacheKey, 0) + 1;
        
        // Lưu lại số lượng yêu cầu
        Cache::put($cacheKey, $requestCount, 60);  // Giới hạn trong 60 giây
        
        // Giới hạn số lượng yêu cầu
        if ($requestCount > 30) {  // Nếu vượt quá 30 request/phút
            Log::channel('iplog')->warning("Cảnh báo: [$route] từ IP: $ip vượt quá giới hạn yêu cầu.");
            return response('Quá nhiều yêu cầu, vui lòng thử lại sau.', 429);
        }

        return $next($request);
    }
}
