<?php

namespace App\Livewire\Appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\On;

class Calendar extends Component
{
    /**
     * Properties
     */
    public Collection $appointments;
    public Collection $doctors;
    public string $calendarView = 'dayGridWeek';
    public string $selectedDate = '';
    public ?int $filterDoctorId = null;
    public ?string $filterStatus = null;
    public ?string $filterStartDate = null;
    public ?string $filterEndDate = null;
    public bool $darkMode = false;
    public ?string $conflictWarning = null;
    public bool $isProcessing = false;

    private AppointmentService $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Mount component
     */
    public function mount()
    {
        $this->doctors = Doctor::active()->get();
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->darkMode = auth()->user()?->theme === 'dark' ?? false;
        $this->loadAppointments();
//        dd($this->getCalendarEvents());
    }

    /**
     * Load appointments
     */
    public function loadAppointments(): void
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->where('start', '>=', Carbon::now()->subMonth())
            ->where('start', '<=', Carbon::now()->addMonths(3));

        if ($this->filterDoctorId) {
            $query->where('doctor_id', $this->filterDoctorId);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterStartDate) {
            $query->where('start', '>=', Carbon::parse($this->filterStartDate));
        }

        if ($this->filterEndDate) {
            $query->where('start', '<=', Carbon::parse($this->filterEndDate)->endOfDay());
        }

        $this->appointments = $query->orderBy('start')->get();
    }

    /**
     * Get calendar events
     */
    public function getCalendarEvents(): array
    {
        return $this->appointments->map(function (Appointment $appointment) {
            return [
                'id' => (string)$appointment->id,
                'title' => $appointment->patient?->getName(),
                'start' => $appointment->start,
                'end' => $appointment->end,
                'classNames' => ['nice-event'],
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $this->getStatusBorderColor($appointment->status),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'status' => $appointment->status,
                    'patientName' => $appointment->patient?->getName() ?? 'Unknown',
                    'doctorName' => $appointment->doctor?->name ?? 'Unassigned',
                    'reason' => $appointment->reason,
                    'notes' => $appointment->notes,
                    'phone' => $appointment->patient?->phone,
                    'file_number' => $appointment->patient?->file_number ?? null,
                    'time' => date('h:i A', strtotime($appointment->appointment_date)),
                ],
            ];
        })->toArray();
    }

    /**
     * Get status colors
     */
    public function getStatusColor(string $status): string
    {
        return match ($status) {
            'scheduled' => '#3B82F6',
            'completed' => '#10B981',
            'cancelled' => '#EF4444',
            'no-show' => '#F97316',
            default => '#6B7280',
        };
    }

    public function getStatusBorderColor(string $status): string
    {
        return match ($status) {
            'scheduled' => '#1E40AF',
            'completed' => '#047857',
            'cancelled' => '#991B1B',
            'no-show' => '#C2410C',
            default => '#374151',
        };
    }

    /**
     * Event handlers
     */
    #[On('dateClick')]
    public function handleDateClick(string $dateStr): void
    {
        $this->dispatch('openModal', component: 'modals.appointments.create-modal', arguments: [
            'date' => $dateStr,
        ]);
    }

    #[On('appointmentCreated')]
    public function refreshCalendar(string $dateStr): void
    {
        $this->dispatch('notify', 'Appointment Created',  'success');
    }

    #[On('eventClick')]
    public function handleEventClick(int $appointmentId): void
    {
        $appointment = Appointment::find($appointmentId);

        if ($appointment && auth()->user()->can('view', $appointment)) {
            $this->dispatch('openModal', component: 'modals.appointments.show-modal', arguments: [
                'appointment' => $appointmentId,
            ]);
        }
    }

    #[On('eventDrop')]
    public function handleEventDrop(int $appointmentId, string $newStart): void
    {
        if ($this->isProcessing) return;
        $this->isProcessing = true;
        try {

            $appointment = $this->appointmentService->reschedule(Appointment::find($appointmentId), $newStart);

            event(new \App\Events\AppointmentRescheduled($appointment));

            $this->dispatch('notify', message: __('Appointment rescheduled'), type: 'success');
            $this->loadAppointments();
        } catch (\Exception $e) {
            $this->dispatch('notify', message: $e->getMessage(), type: 'error');
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Filter methods
     */
    public function updateFilterDoctor(?int $doctorId): void
    {
        $this->filterDoctorId = $doctorId;
        $this->loadAppointments();
    }

    public function updateFilterStatus(?string $status): void
    {
        $this->filterStatus = $status;
        $this->loadAppointments();
    }

    public function updateDateFilter(?string $startDate, ?string $endDate): void
    {
        $this->filterStartDate = $startDate;
        $this->filterEndDate = $endDate;
        $this->loadAppointments();
    }

    public function clearFilters(): void
    {
        $this->filterDoctorId = null;
        $this->filterStatus = null;
        $this->filterStartDate = null;
        $this->filterEndDate = null;
        $this->conflictWarning = null;
        $this->loadAppointments();
    }

    public function toggleDarkMode(): void
    {
        $this->darkMode = !$this->darkMode;
        auth()->user()->update(['dark_mode' => $this->darkMode]);
    }

    public function getAvailableStatuses(): array
    {
        return [
            'scheduled' => __('Scheduled'),
            'completed' => __('Completed'),
            'cancelled' => __('Cancelled'),
            'no-show' => __('Not show'),
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.appointments.calendar', [
            'calendarEvents' => $this->getCalendarEvents(),
            'statuses' => $this->getAvailableStatuses(),
        ]);
    }
}
