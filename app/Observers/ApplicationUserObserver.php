<?php

namespace App\Observers;

use App\Models\ApplicationUser;
use Illuminate\Support\Facades\Mail;

class ApplicationUserObserver
{
    /**
     * Handle the ApplicationUser "created" event.
     */
    public function created(ApplicationUser $applicationUser): void
    {
        Mail::raw('Пользовать под id:' . $applicationUser->user_id . ', имя:' . $applicationUser->user->name . ' сделал запрос на изменение роли - ' . $applicationUser->role, function ($message) {
            $message
                ->to(config('admin.email'))
                ->subject('Заявка на смену компании пользователя');
        });
    }

    /**
     * Handle the ApplicationUser "updated" event.
     */
    public function updated(ApplicationUser $applicationUser): void
    {
        //
    }

    /**
     * Handle the ApplicationUser "deleted" event.
     */
    public function deleted(ApplicationUser $applicationUser): void
    {
        //
    }

    /**
     * Handle the ApplicationUser "restored" event.
     */
    public function restored(ApplicationUser $applicationUser): void
    {
        //
    }

    /**
     * Handle the ApplicationUser "force deleted" event.
     */
    public function forceDeleted(ApplicationUser $applicationUser): void
    {
        //
    }
}
