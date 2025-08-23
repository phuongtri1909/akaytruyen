<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Story;
use App\Repositories\Story\StoryRepositoryInterface;

class TestStoryCache extends Command
{
    protected $signature = 'cache:test-story {slug?}';
    protected $description = 'Test story caching system performance and cache keys';

    public function __construct(
        protected StoryRepositoryInterface $storyRepository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $slug = $this->argument('slug') ?? 'sample-story-slug';

        $this->info("Testing Story Cache System for: {$slug}");
        $this->newLine();

        // Test 1: Query count before cache
        $this->info("=== Test 1: Query Count Before Cache ===");
        DB::enableQueryLog();

        $story = $this->storyRepository->getStoryBySlugOptimized($slug);
        if (!$story) {
            $this->error("Story with slug '{$slug}' not found!");
            return;
        }

        $queries = DB::getQueryLog();
        $this->info("Queries executed (without cache): " . count($queries));
        foreach ($queries as $i => $query) {
            $this->line("  " . ($i+1) . ". " . substr($query['query'], 0, 80) . "...");
        }
        DB::disableQueryLog();

                // Test 2: Query count with cache
        $this->info("\n=== Test 2: Query Count With Cache ===");
        Cache::forget("story:detail:{$slug}");

        DB::enableQueryLog();
        $cachedStory = $this->storyRepository->getCachedStoryDetail($slug);
        $queries = DB::getQueryLog();
        $this->info("Queries executed (first cache hit): " . count($queries));

        // Second hit should be 0 queries
        DB::flushQueryLog();
        $cachedStory2 = $this->storyRepository->getCachedStoryDetail($slug);
        $queries = DB::getQueryLog();
        $this->info("Queries executed (second cache hit): " . count($queries));
        DB::disableQueryLog();

        // Test chapter ranges cache
        $this->info("\n=== Test 2b: Chapter Ranges Cache ===");
        DB::enableQueryLog();
        $ranges = $this->storyRepository->getCachedChapterRanges($story->id);
        $queries = DB::getQueryLog();
        $this->info("Queries for chapter ranges (first hit): " . count($queries));

        DB::flushQueryLog();
        $ranges2 = $this->storyRepository->getCachedChapterRanges($story->id);
        $queries = DB::getQueryLog();
        $this->info("Queries for chapter ranges (second hit): " . count($queries));
        $this->info("Chapter ranges count: " . count($ranges));
        DB::disableQueryLog();

        // Test 3: Show cache keys
        $this->info("\n=== Test 3: Cache Keys Created ===");
        $cacheKeys = [
            "story:detail:{$slug}",
            "story:stats:{$story->id}",
            "story:chapters_new:{$story->id}",
            "story:chapter_ranges:{$story->id}",
            "story:chapters:{$story->id}:page:1:order:desc",
            "app:user_stats",
            "app:categories"
        ];

        foreach ($cacheKeys as $key) {
            $exists = Cache::has($key) ? '✓' : '✗';
            $this->line("{$exists} {$key}");
        }

        // Test 4: Cache invalidation
        $this->info("\n=== Test 4: Cache Invalidation Test ===");
        $this->info("Updating story to test cache invalidation...");

        $originalName = $story->name;
        $story->update(['name' => $originalName . ' (Updated)']);

        $cacheExists = Cache::has("story:detail:{$slug}") ? '✗ Failed' : '✓ Success';
        $this->line("Cache cleared after story update: {$cacheExists}");

        // Restore original name
        $story->update(['name' => $originalName]);

        $this->newLine();
        $this->info("Cache testing completed!");
    }
}
