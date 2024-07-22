<?php

namespace MohamadZatar\Nafath\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificationVerified
{
    use Dispatchable, SerializesModels;

    public $userId;
    public $notification;

    /**
     * Create a new event instance.
     *
     * @param  int  $userId
     * @param  \App\Models\Notification  $notification
     * @return void
     */
    public function __construct($userId, $notification)
    {
        $this->userId = $userId;
        $this->notification = $notification;
    }
}
