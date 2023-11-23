<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Recruitment;

class ChatObserver
{
    /**
     * Handle the Chat "created" event.
     */
    public function created(Chat $chat): void
    {
        if ($chat->chatsable_type == 'App\Models\Recruitment') {
            $recruitment = Recruitment::find($chat->chatsable_id);

            ChatUser::create([
                'chat_id' => $chat->id,
                'user_id' => $recruitment
            ]);
        }

        ChatUser::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id()
        ]);
    }

    /**
     * Handle the Chat "updated" event.
     */
    public function updated(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "deleted" event.
     */
    public function deleted(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "restored" event.
     */
    public function restored(Chat $chat): void
    {
        //
    }

    /**
     * Handle the Chat "force deleted" event.
     */
    public function forceDeleted(Chat $chat): void
    {
        //
    }
}
