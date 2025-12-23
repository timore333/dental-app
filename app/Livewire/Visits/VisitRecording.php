<?php

namespace App\Livewire\Visits;

use App\Events\VisitRecorded;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Visit;
use App\Services\AppointmentService;
use App\Services\VisitService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VisitRecording extends Component
{
    public $appointmentId;
    public Patient $patient;
    public Doctor $doctor;
    public $visitDate;
    public $chiefComplaint = '';
    public $diagnosis = '';
    public $treatmentNotes = '';

    public $procedurePrice = null;
    public $selectedProcedure = null;
    public $selectedProcedures = [];

    protected $rules = [
        'patient' => 'required',
        'doctor' => 'required',
        'visitDate' => 'required|date|before_or_equal:now',
        'chief_complaint' => 'required|string|max:1000',
        'diagnosis' => 'nullable|string|max:1000',
        'treatmentNotes' => 'nullable|string|max:1000',
        'selectedProcedures' => 'array|min:1',
    ];

    public function mount($appointmentId = null)
    {
        if ($appointmentId) {
            $appointment = Appointment::with('patient', 'doctor')->findOrFail($appointmentId);
            $this->appointmentId = $appointmentId;
            $this->patient = $appointment->patient;
            $this->doctor = $appointment->doctor;
            $this->visitDate = now()->format('Y-m-d');
        } else {
            $this->visitDate = now()->format('Y-m-d');
        }
    }

    /**
     * When procedure is selected, get the appropriate price
     * based on patient type (insurance or cash)
     */
//    #[\Livewire\Attributes\On('update:selectedProcedure')]
    public function updatedSelectedProcedure($value)
    {
        $this->procedurePrice = null;

        if (!$value) {
            return;
        }

        $procedure = Procedure::find($value);

        if (!$procedure) {
            $this->dispatch('notify', __('Selected procedure not found'));
            $this->reset('selectedProcedure');
            return;
        }

        $price = $procedure->getPriceForPatient($this->patient);

        if ($price === null) {
            $this->dispatch('notify', __('No price available for this procedure for patient type'));
            $this->reset('selectedProcedure');
            return;
        }

        $this->procedurePrice = (float) $price;
    }

    /**
     * Add selected procedure to procedures list
     */
    public function addProcedure()
    {
        if (!$this->selectedProcedure) {
            $this->dispatch('notify', __('Please select a procedure'));
            return;
        }

        $procedure = Procedure::find($this->selectedProcedure);

        if (!$procedure || $this->procedurePrice === null) {

            $this->dispatch('notify', __('Please select a procedure with valid price'));
            return;
        }

        $this->selectedProcedures[] = [
            'procedure_id' => $procedure->id,
            'name' => $procedure->name,
            'code' => $procedure->code,
            'price' => floatval($this->procedurePrice),
        ];

        $this->reset('selectedProcedure', 'procedurePrice');

    }

    /**
     * Remove procedure from list
     */
    public function removeProcedure($index)
    {
        unset($this->selectedProcedures[$index]);
        $this->selectedProcedures = array_values($this->selectedProcedures);
    }

    /**
     * Calculate total price of all procedures
     */
    public function calculateTotal()
    {
        if (empty($this->selectedProcedures)) {
            return 0;
        }

        return array_sum(array_column($this->selectedProcedures, 'price'));
    }

    /**
     * Submit visit recording
     */
    public function submitVisit()
    {
//        $this->validate();

        if (empty($this->selectedProcedures)) {
            $this->dispatch('notify', __('Add at least one procedure'));
            return;
        }


        try {
            $visitData = [
                'appointment_id' => $this->appointmentId,
                'patient_id' => $this->patient->id,
                'doctor_id' => $this->doctor->id,
                'visit_date' => $this->visitDate,
                'chief_complaint' => $this->chiefComplaint,
                'diagnosis' => $this->diagnosis,
                'treatment_notes' => $this->treatmentNotes,
                'created_by' => Auth::id(),
            ];

            $visitService = new VisitService();
            $visit = $visitService->createVisit($visitData);

            foreach ($this->selectedProcedures as $procedure) {
                $visitService->addProcedureToVisit(
                    $visit,
                    $procedure['procedure_id'],
                    $procedure['price']
                );
            }

            event(new VisitRecorded($visit));

            // mark appointment as completed
           $appointmentService =  new AppointmentService();
           $appointmentService->markCompleted(Appointment::find($this->appointmentId));

            $this->dispatch('notify', type: 'success', message: __('Visit recorded and bill created'));
            redirect()->route('visits.show', ['id' => $visit->id]);

        } catch (\Exception $e) {

            $this->addError('general', __('Error recording visit: ' . $e->getMessage()));
        }
    }

    public function render()
    {
        $procedures = Procedure::active()->orderBy('name')->get();
        return view('livewire.visits.visit-recording', ['procedures' => $procedures]);
    }
}
