<?php

namespace MohamadZatar\Nafath\app\Listeners;

use MohamadZatar\Nafath\Events\VerificationVerified;

class HandleVerificationVerified
{
    /**
     * Handle the event.
     *
     * @param  \MohamadZatar\Nafath\Events\VerificationVerified  $event
     * @return void
     */
    public function handle(VerificationVerified $event)
    {
        // Custom logic for handling verification verification
        // Example: Log or send additional notifications
    }
}
