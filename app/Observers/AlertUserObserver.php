<?php

namespace App\Observers;

use App\Models\AlertUser;

class AlertUserObserver
{
    /**
     * Handle the AlertUser "created" event.
     */
    public function created(AlertUser $alertUser): void
    {
        //
    }

    /**
     * Handle the AlertUser "updated" event.
     */
    public function updated(AlertUser $alertUser): void
    {
        if ($alertUser->status == 'active') {
            
        }
    }

    /**
     * Handle the AlertUser "deleted" event.
     */
    public function deleted(AlertUser $alertUser): void
    {
        //
    }

    /**
     * Handle the AlertUser "restored" event.
     */
    public function restored(AlertUser $alertUser): void
    {
        //
    }

    /**
     * Handle the AlertUser "force deleted" event.
     */
    public function forceDeleted(AlertUser $alertUser): void
    {
        //
    }
}
