<?php
namespace App\Livewire\Insurance;
use App\Models\InsuranceApproval;
use App\Models\InsuranceRequest;
use App\Models\Visit;
use Livewire\Component;
use Livewire\WithFileUploads;

class InsuranceBillingFlow extends Component
{
    use WithFileUploads;

    public $visit;
    public $insuranceCompany;
    public $procedures = [];
    public $step = 1;
    public $requestDocument;
    public $approvalDocument;
    public $approvedProcedures = [];
    public $rejectedProcedures = [];
    public $approvedAmount = 0;
    public $approvalNotes = '';

    public function mount($visitId)
    {
        $this->visit = Visit::with('patient', 'doctor', 'procedures')->findOrFail($visitId);
        $this->procedures = $this->visit->procedures->toArray();
    }

    public function render()
    {
        return view('livewire.insurance.insurance-billing-flow', [
            'visit' => $this->visit,
        ]);
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validateRequestStep();
            $this->step = 2;
        } elseif ($this->step == 2) {
            $this->validateApprovalStep();
            $this->step = 3;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    private function validateRequestStep()
    {
        // Validate and upload request document
    }

    private function validateApprovalStep()
    {
        // Validate and upload approval document
    }

    public function submitRequest()
    {
        $this->validateRequestStep();

        try {
            $insuranceRequest = InsuranceRequest::create([
                'patient_id' => $this->visit->patient_id,
                'insurance_company_id' => $this->visit->insurance_company_id,
                'doctor_id' => $this->visit->doctor_id,
                'visit_id' => $this->visit->id,
                'total_cost' => collect($this->procedures)->sum('price'),
                'request_document_path' => $this->requestDocument?->store('insurance_requests'),
                'status' => 'pending',
            ]);

            foreach ($this->procedures as $procedure) {
                $insuranceRequest->procedures()->attach($procedure['id']);
            }

            $this->dispatch('notify', type: 'success', message: __('Insurance request created'));
            $this->step = 2;
        } catch (\Exception $e) {
            $this->addError('general', __('Error creating request'));
        }
    }

    public function recordApproval()
    {
        try {
            $approval = InsuranceApproval::create([
                'insurance_request_id' => $this->visit->insurance_request?->id,
                'approved_amount' => $this->approvedAmount,
                'approval_notes' => $this->approvalNotes,
                'approval_document_path' => $this->approvalDocument?->store('insurance_approvals'),
                'created_by' => auth()->id(),
            ]);

            foreach ($this->approvedProcedures as $procId) {
                $approval->approvedProcedures()->attach($procId, ['status' => 'approved']);
            }

            foreach ($this->rejectedProcedures as $procId) {
                $approval->approvedProcedures()->attach($procId, ['status' => 'rejected']);
            }

            $this->dispatch('notify', type: 'success', message: __('Approval recorded'));
            $this->step = 3;
        } catch (\Exception $e) {
            $this->addError('general', __('Error recording approval'));
        }
    }

    public function createBill()
    {
        try {
            // Create bill from approved procedures
            $this->dispatch('notify', type: 'success', message: __('Insurance bill created'));
            redirect()->route('billing.cash', ['bill_id' => 'bill_id']);
        } catch (\Exception $e) {
            $this->addError('general', __('Error creating bill'));
        }
    }
}
?>
