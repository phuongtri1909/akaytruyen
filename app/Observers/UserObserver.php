<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function created(User $user): void { $this->flushIfRelevant($user); }
    public function updated(User $user): void { $this->flushIfRelevant($user); }
    public function deleted(User $user): void { $this->flushIfRelevant($user); }

    protected function flushIfRelevant(User $user): void
    {
        if ($user->wasChanged('rating')) {
            Cache::forget('stats:total_rating');
            Cache::forget('app:stats');
        }
    }
}


