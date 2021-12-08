<?php

namespace App\Providers;

use App\Events\BannedUser;
use App\Events\UnbanUser;
use App\Listeners\Banned\EnterBannedUser;
use App\Listeners\Banned\RestoreBannedUser;
use App\Listeners\Banned\SendBannedUserNotification;
use App\Listeners\Banned\SendRestoreUserNotification;
use App\Listeners\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginHistory::class,
        ],
        BannedUser::class => [
            EnterBannedUser::class,
            SendBannedUserNotification::class,
        ],
        UnbanUser::class => [
            RestoreBannedUser::class,
            SendRestoreUserNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * php artisan make:listener Banned/RestoreBannedUser --event=UnbanUser
     * php artisan make:listener Banned/SendRestoreUserNotification --event=UnbanUser
     * @return void
     */
    public function boot()
    {
        //
    }
}
