<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Livechat;
use App\Models\LivechatReaction;

class ClearCommentCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-comments {--all : Clear all comment related cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all comment and reaction cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing comment cache...');

        // Clear comment count cache
        Cache::forget('total_main_comments');
        $this->line('✓ Cleared total comment count cache');

        // Clear comment page cache
        for ($i = 10; $i <= 100; $i += 10) {
            Cache::forget("comments_page_{$i}");
        }
        $this->line('✓ Cleared comment page cache');

        // Clear reaction cache
        $commentIds = Livechat::pluck('id')->toArray();
        foreach ($commentIds as $commentId) {
            Cache::forget("comment_reactions_{$commentId}");
            Cache::forget("comment_reaction_counts_{$commentId}");
        }
        $this->line('✓ Cleared reaction cache');

        // Clear user cache
        Cache::forget('users_with_roles');
        $this->line('✓ Cleared user cache');

        if ($this->option('all')) {
            // Clear all cache
            Cache::flush();
            $this->line('✓ Cleared all application cache');
        }

        $this->info('Comment cache cleared successfully!');

        return Command::SUCCESS;
    }
}
