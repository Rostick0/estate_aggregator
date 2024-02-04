<?php

namespace App\Observers;

use App\Models\ApplicationCompany;
use Illuminate\Support\Facades\Mail;

class ApplicationCompanyObserver
{
    /**
     * Handle the ApplicationCompany "created" event.
     */
    public function created(ApplicationCompany $applicationCompany): void
    {
        Mail::raw('Пользовать под id:' . $applicationCompany->user_id . ', имя:' . $applicationCompany->user->name . ' сделал запрос на изменение компании id:' . $applicationCompany->company_id . ' название:' . $applicationCompany?->company?->owner?->name, function ($message) {
            $message
                ->to(config('admin.email'))
                ->subject('Заявка на смену компании пользователя');
        });
    }

    /**
     * Handle the ApplicationCompany "updated" event.
     */
    public function updated(ApplicationCompany $applicationCompany): void
    {
        //
    }

    /**
     * Handle the ApplicationCompany "deleted" event.
     */
    public function deleted(ApplicationCompany $applicationCompany): void
    {
        //
    }

    /**
     * Handle the ApplicationCompany "restored" event.
     */
    public function restored(ApplicationCompany $applicationCompany): void
    {
        //
    }

    /**
     * Handle the ApplicationCompany "force deleted" event.
     */
    public function forceDeleted(ApplicationCompany $applicationCompany): void
    {
        //
    }
}
