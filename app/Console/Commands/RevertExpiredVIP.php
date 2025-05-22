<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RevertExpiredVIP extends Command
{
    protected $signature = 'vip:revert';
    protected $description = 'Xoá role VIP đã hết hạn và trả về role thường';

    public function handle(): void
    {
        $expiredUsers = DB::table('model_has_roles')
            ->where('role_id', 6)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredUsers as $user) {
            // Xoá role VIP
            DB::table('model_has_roles')
                ->where('model_id', $user->model_id)
                ->where('model_type', $user->model_type)
                ->where('role_id', 6)
                ->delete();

            // Gán lại role user (role_id = 5)
            DB::table('model_has_roles')->updateOrInsert([
                'model_id'   => $user->model_id,
                'model_type' => $user->model_type,
                'role_id'    => 5,
            ]);
        }

        $this->info("Đã xử lý " . count($expiredUsers) . " user hết hạn VIP.");
    }
}

