<?php
namespace App\Livewire\Visits;
use App\Models\Doctor;
use App\Models\Visit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class VisitsList extends Component
{
    use WithPagination;

    public $search = '';
    public $doctorFilter = '';
    public $dateFilter = '';
    public $billStatusFilter = '';
    public $perPage = 15;

    public function render()
    {
        $query = Visit::with('patient', 'doctor', 'bill')
            ->where('doctor_id', Auth::user()->doctor?->id)
            ->orWhere('created_by', Auth::id());

        if (!empty($this->search)) {
            $query->whereHas('patient', fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            );
        }

        if (!empty($this->doctorFilter)) {
            $query->where('doctor_id', $this->doctorFilter);
        }

        if (!empty($this->dateFilter)) {
            $query->whereDate('visit_date', $this->dateFilter);
        }

        if (!empty($this->billStatusFilter)) {
            $query->whereHas('bill', fn($q) =>
                $q->where('payment_status', $this->billStatusFilter)
            );
        }

        return view('livewire.visits.visits-list', [
            'visits' => $query->orderBy('visit_date', 'desc')->paginate($this->perPage),
            'doctors' => Doctor::orderBy('name')->get(),
        ]);
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedDoctorFilter() { $this->resetPage(); }
    public function updatedDateFilter() { $this->resetPage(); }
    public function updatedBillStatusFilter() { $this->resetPage(); }
}
?>
