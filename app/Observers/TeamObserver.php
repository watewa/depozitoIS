<?php

namespace App\Observers;

use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class TeamObserver
{
    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team)
    {
        if ($team->isDirty('picture')) {
            $oldPicture = $team->getOriginal('picture');
            if ($oldPicture) {
                Storage::disk('public')->delete($oldPicture);
            }
        }
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team)
    {
        if ($team->picture) {
            Storage::disk('public')->delete($team->picture);
        }
    }

    /**
     * Handle the Team "restored" event.
     */
    public function restored(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "force deleted" event.
     */
    public function forceDeleted(Team $team): void
    {
        //
    }
}
