<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\ApplicationCompany;
use App\Models\ApplicationUser;
use App\Observers\ApplicationCompanyObserver;
use App\Observers\ApplicationUserObserver;
use App\Observers\ChatObserver;
use App\Observers\MessageObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Chat::observe(ChatObserver::class);
        Message::observe(MessageObserver::class);
        ApplicationCompany::observe(ApplicationCompanyObserver::class);
        ApplicationUser::observe(ApplicationUserObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
