<?php

namespace App\Observers;

use App\Models\Chapter;
use Illuminate\Support\Facades\Cache;

class ChapterObserver
{
    public function created(Chapter $chapter): void { $this->flush(); }
    public function updated(Chapter $chapter): void {
        $changedKeys = array_keys($chapter->getChanges());
        $nonCritical = ['views', 'updated_at'];
        $meaningfulChanges = array_diff($changedKeys, $nonCritical);

        if (empty($meaningfulChanges)) {
            // Skip cache flush for view-only updates to avoid thrashing
            return;
        }

        $this->flush();
    }
    public function deleted(Chapter $chapter): void { $this->flush(); }

    protected function flush(): void
    {
        Cache::forget('home:stories_hot');
        Cache::forget('home:stories_new');
        Cache::forget('home:stories_full');
        Cache::forget('stats:total_chapter');
        Cache::forget('home:stories_hot:all');
        Cache::forget('app:stats');
    }
}


