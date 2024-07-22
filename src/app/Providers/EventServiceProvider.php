<?php

namespace MohamadZatar\Nafath\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use MohamadZatar\Nafath\app\Listeners\app\Listeners\HandleVerificationRejected;
use MohamadZatar\Nafath\app\Listeners\app\Listeners\HandleVerificationVerified;
use MohamadZatar\Nafath\Events\VerificationRejected;
use MohamadZatar\Nafath\Events\VerificationVerified;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        VerificationVerified::class => [
            HandleVerificationVerified::class,
        ],
        VerificationRejected::class => [
            HandleVerificationRejected::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
