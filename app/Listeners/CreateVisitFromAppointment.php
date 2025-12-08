<?php
namespace App\Listeners;
use App\Events\AppointmentCompleted;
use App\Models\Visit;
use App\Services\VisitService;

class CreateVisitFromAppointment
{
    public function handle(AppointmentCompleted $event)
    {
        $visitService = app(VisitService::class);
        $visitService->createVisitFromAppointment($event->appointment);
    }
}
