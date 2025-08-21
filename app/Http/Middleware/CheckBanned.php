<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CheckBanned
{
    /**
     * Xử lý yêu cầu đầu vào.
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $cacheKey = "banned_ip:{$ip}";

        // Check cache first - if exists and is banned, abort immediately
        if (Cache::get($cacheKey) === true) {
            abort(403, 'IP của bạn đã bị cấm vì vi phạm tiêu chuẩn cộng đồng >.<.');
        }

        // If cached as non-banned, continue
        if (Cache::has($cacheKey)) {
            return $next($request);
        }

        // Check database and cache result
        if (DB::table('banned_ips')->where('ip_address', $ip)->exists()) {
            // Cache banned IP for 1 hour to avoid repeated queries
            Cache::put($cacheKey, true, now()->addHour());
            abort(403, 'IP của bạn đã bị cấm vì vi phạm tiêu chuẩn cộng đồng >.<.');
        }

        // Cache non-banned IP for 5 minutes to avoid repeated queries
        Cache::put($cacheKey, false, now()->addMinutes(5));

        return $next($request);
    }
}
