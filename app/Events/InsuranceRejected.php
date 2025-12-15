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
 * InsuranceRejected Event
 * Dispatched when an insurance claim is rejected
 * Listened by NotifyPatientOnInsuranceRejected listener
 */
class InsuranceRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The insurance instance
     *
     * @var Insurance
     */
    public $insurance;

    /**
     * Rejection reason
     *
     * @var string
     */
    public $reason;

    /**
     * Create a new event instance.
     *
     * @param Insurance $insurance
     * @param string|null $reason
     */
    public function __construct(Insurance $insurance, $reason = null)
    {
        $this->insurance = $insurance;
        $this->reason = $reason;
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
