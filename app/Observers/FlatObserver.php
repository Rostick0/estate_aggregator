<?php

namespace App\Observers;

use App\Models\Flat;

class FlatObserver
{
    /**
     * Handle the Flat "created" event.
     */
    public function created(Flat $flat): void
    {
        //
    }

    /**
     * Handle the Flat "updated" event.
     */
    public function updated(Flat $flat): void
    {
        //
    }

    /**
     * Handle the Flat "deleted" event.
     */
    public function deleted(Flat $flat): void
    {
        //
    }

    /**
     * Handle the Flat "restored" event.
     */
    public function restored(Flat $flat): void
    {
        //
    }

    /**
     * Handle the Flat "force deleted" event.
     */
    public function forceDeleted(Flat $flat): void
    {
        //
    }
}
