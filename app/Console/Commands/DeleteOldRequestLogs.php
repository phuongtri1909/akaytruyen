<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldRequestLogs extends Command
{
    protected $signature = 'logs:delete-old';
    protected $description = 'Delete request logs older than 30 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        DB::table('request_logs')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $this->info('Old request logs deleted successfully.');
    }
}
