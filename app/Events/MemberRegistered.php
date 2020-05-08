<?php

namespace App\Events;

use App\Models\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberRegistered
{
    use SerializesModels;

    public $customer;
    public $member;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customer, $member)
    {
        $this->customer = $customer;
        $this->member = $member;
    }
}
