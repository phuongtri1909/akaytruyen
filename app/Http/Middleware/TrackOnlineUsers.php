<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class TrackOnlineUsers
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $userId = auth()->check() ? auth()->id() : null;


        // Cập nhật hoạt động online
        \DB::table('online_users')->updateOrInsert(
            ['ip' => $ip],
            [
                'user_id' => $userId,
                'last_activity' => now(),
            ]
        );

        // Ghi log truy cập
        DB::table('request_logs')->insert([
            'ip' => $ip,
            'url' => $request->fullUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $next($request);
    }
}

