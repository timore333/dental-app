<?php
namespace App\Listeners;
use App\Events\VisitRecorded;
use App\Services\BillService;

class CreateBillFromVisit
{
    public function handle(VisitRecorded $event)
    {
        $billService = app(BillService::class);
        $bill = $billService->createBillFromVisit($event->visit);

        // Update visit with bill reference
        $event->visit->update(['bill_id' => $bill->id]);
    }
}
