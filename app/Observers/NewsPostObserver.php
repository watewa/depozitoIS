<?php

namespace App\Observers;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Storage;

class NewsPostObserver
{
    /**
     * Handle the NewsPost "created" event.
     */
    public function created(NewsPost $newsPost): void
    {
        //
    }

    /**
     * Handle the NewsPost "updated" event.
     */
    public function updated(NewsPost $newsPost): void
    {
        if (! is_null($newsPost->getOriginal('thumbnail')) && $newsPost->isDirty('thumbnail')) {
            Storage::disk('public')->delete($newsPost->getOriginal('thumbnail'));
        }
    }

    /**
     * Handle the NewsPost "deleted" event.
     */
    public function deleted(NewsPost $newsPost): void
    {
        if (! is_null($newsPost->thumbnail)) {
            Storage::disk('public')->delete($newsPost->thumbnail);
        }
    }

    /**
     * Handle the NewsPost "restored" event.
     */
    public function restored(NewsPost $newsPost): void
    {
        //
    }

    /**
     * Handle the NewsPost "force deleted" event.
     */
    public function forceDeleted(NewsPost $newsPost): void
    {
        //
    }
}
