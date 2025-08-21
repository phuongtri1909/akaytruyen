<?php

namespace App\Observers;

use App\Models\Banned_ip;
use Illuminate\Support\Facades\Cache;

class BannedIpObserver
{
    public function created(Banned_ip $bannedIp): void { $this->flush($bannedIp); }
    public function updated(Banned_ip $bannedIp): void { $this->flush($bannedIp); }
    public function deleted(Banned_ip $bannedIp): void { $this->flush($bannedIp); }

    protected function flush(Banned_ip $bannedIp): void
    {
        // Clear specific IP cache
        Cache::forget("banned_ip:{$bannedIp->ip_address}");

        // Also clear any cached user-related data if user_id exists
        if ($bannedIp->user_id) {
            Cache::forget("banned_ip:user:{$bannedIp->user_id}");
        }
    }
}
