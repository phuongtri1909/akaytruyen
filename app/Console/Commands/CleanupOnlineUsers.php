<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupOnlineUsers extends Command
{
    protected $signature = 'online-users:cleanup';
    protected $description = 'Xoá người dùng online quá 10 phút';

    public function handle()
    {
        DB::table('online_users')
            ->where('last_activity', '<', now()->subMinutes(10))
            ->delete();

        $this->info('Cleaned up old online users.');
    }
}
