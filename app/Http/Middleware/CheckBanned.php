<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckBanned
{
    /**
     * Xử lý yêu cầu đầu vào.
     */
    public function handle(Request $request, Closure $next)
    {
        if (DB::table('banned_ips')->where('ip_address', $request->ip())->exists()) {
            abort(403, 'IP của bạn đã bị cấm vì vi phạm tiêu chuẩn cộng đồng >.<.');
        }
    
        return $next($request);
    }
}
