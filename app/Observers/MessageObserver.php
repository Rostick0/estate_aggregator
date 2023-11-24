<?php

namespace App\Observers;

use App\Events\Message as EventsMessage;
use App\Models\Message;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        EventsMessage::dispatch([
            'data' => $message,
            'type' => 'create'
        ]);
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        EventsMessage::dispatch([
            'data' => $message,
            'type' => 'update'
        ]);
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        EventsMessage::dispatch([
            'data' => $message,
            'type' => 'delete'
        ]);
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
