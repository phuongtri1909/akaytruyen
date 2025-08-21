<?php

namespace App\Observers;

use App\Models\Story;
use Illuminate\Support\Facades\Cache;

class StoryObserver
{
    public function created(Story $story): void { $this->flush(); }
    public function updated(Story $story): void { $this->flush(); }
    public function deleted(Story $story): void { $this->flush(); }

    protected function flush(): void
    {
        Cache::forget('home:stories_hot');
        Cache::forget('home:stories_new_ids');
        Cache::forget('home:stories_new');
        Cache::forget('home:stories_full_ids');
        Cache::forget('home:stories_full');
        Cache::forget('stats:total_story');
        Cache::forget('home:stories_hot:all');
        Cache::forget('app:stats');
        // Category-specific caches are dynamic; a simple approach is to flush the entire cache tag if using tagged cache.
    }
}


