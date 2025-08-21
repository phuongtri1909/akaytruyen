<?php

namespace App\Observers;

use App\Models\Donation;
use Illuminate\Support\Facades\Cache;

class DonationObserver
{
    public function created(Donation $donation): void { $this->flush(); }
    public function updated(Donation $donation): void { $this->flush(); }
    public function deleted(Donation $donation): void { $this->flush(); }

    protected function flush(): void
    {
        Cache::forget('donors:months');
        // Invalidate all month-year top donor caches by key pattern if store supports it; otherwise rely on short TTL
    }
}


