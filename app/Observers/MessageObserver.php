<?php

namespace App\Observers;

use App\Events\Message as EventsMessage;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use Carbon\Carbon;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        Chat::find($message->chat_id)->update(['last_message_created_at' => Carbon::now()]);

        if ($message->user_id != auth()->id()) {
            ChatUser::firstWhere([
                ['chat_id', '=', $message->chat_id],
                ['user_id', '!=', $message->user_id],
            ])->update([
                'is_read' => 0
            ]);
        }
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
    
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
