<?php

namespace App\Events;

use App\Models\Insurance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * InsuranceApproved Event
 * Dispatched when an insurance claim is approved
 * Listened by NotifyPatientOnInsuranceApproved listener
 */
class InsuranceApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The insurance instance
     *
     * @var Insurance
     */
    public $insurance;

    /**
     * Create a new event instance.
     *
     * @param Insurance $insurance
     */
    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
