<?php
namespace App\Livewire\Visits;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Visit;
use App\Services\VisitService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VisitRecording extends Component
{
    public $visit;
    public $patient;
    public $doctor;
    public $visitDate;
    public $chiefComplaint = '';
    public $diagnosis = '';
    public $treatmentNotes = '';
    public $procedures = [];
    public $selectedProcedure = '';
    public $procedurePrice = '';
    public $showProcedureModal = false;
    public $tab = 'info';
    public $appointmentId = null;

    protected $rules = [
        'patient' => 'required',
        'doctor' => 'required',
        'visitDate' => 'required|date|before_or_equal:now',
        'chiefComplaint' => 'required|string|max:1000',
        'diagnosis' => 'nullable|string|max:1000',
        'treatmentNotes' => 'nullable|string|max:1000',
        'procedures' => 'array|min:0',
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

    public function render()
    {
        return view('livewire.visits.visit-recording', [
            'procedures' => Procedure::orderBy('name')->get(),
        ]);
    }

    public function selectProcedure()
    {
        if ($this->selectedProcedure) {
            $procedure = Procedure::find($this->selectedProcedure);
            if ($procedure) {
                $this->procedurePrice = $procedure->price;
            }
        }
    }

    public function addProcedure()
    {
        if (!$this->selectedProcedure || !$this->procedurePrice) {
            $this->addError('selectedProcedure', __('Please select a procedure'));
            return;
        }

        $procedure = Procedure::find($this->selectedProcedure);
        $this->procedures[] = [
            'procedure_id' => $procedure->id,
            'name' => $procedure->name,
            'code' => $procedure->code,
            'price' => floatval($this->procedurePrice),
        ];

        $this->selectedProcedure = '';
        $this->procedurePrice = '';
        $this->showProcedureModal = false;
    }

    public function removeProcedure($index)
    {
        unset($this->procedures[$index]);
        $this->procedures = array_values($this->procedures);
    }

    public function calculateTotal()
    {
        return array_sum(array_column($this->procedures, 'price'));
    }

    public function submitVisit()
    {
        $this->validate();

        if (empty($this->procedures)) {
            $this->addError('procedures', __('Add at least one procedure'));
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

            foreach ($this->procedures as $procedure) {
                $visitService->addProcedureToVisit($visit, $procedure['procedure_id'], $procedure['price']);
            }

            event(new \App\Events\VisitRecorded($visit));

            $this->dispatch('notify', type: 'success', message: __('Visit recorded and bill created'));
            redirect()->route('billing.cash', ['bill_id' => $visit->bill?->id]);
        } catch (\Exception $e) {
            $this->addError('general', __('Error recording visit'));
        }
    }
}
?>
