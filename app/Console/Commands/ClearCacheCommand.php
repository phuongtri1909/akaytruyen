<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class ClearCacheCommand extends Command
{
    protected $signature = 'cache:clear-custom';
    protected $description = 'Clear application cache';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('cache:clear');
        $this->info('Cache cleared successfully!');
    }
}
