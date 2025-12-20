<?php

namespace App\Livewire\Modals\Patients;


use App\Services\Patients\PatientImportExportService;
use Cloudstudio\Modal\LivewireModal;
use Exception;
use Illuminate\Support\Facades\Log;

class PatientExportModal extends LivewireModal
{
    public $exportFormat = 'excel';
    public $exportTemplate = 'complete';
    public $selectedFields = [];
    public $filterCategory = 'all';
    public $filterType = 'all';
    public $filterStatus = 'all';
    public $filterFromDate = '';
    public $filterToDate = '';
    public $errorMessages = [];
    public $successMessage = '';

    public $exportService;
    public function boot(PatientImportExportService $service)
    {
        $this->exportService = $service;
    }

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount()
    {
        $this->selectedFields = array_keys($this->exportService->getExportableFields());
    }

    public function export()
    {
        try {
            $filters = [
                'category' => $this->filterCategory,
                'type' => $this->filterType,
                'status' => $this->filterStatus,
                'from_date' => $this->filterFromDate,
                'to_date' => $this->filterToDate,
            ];

            match ($this->exportFormat) {
                'excel' => $this->exportService->exportToExcel($filters, $this->selectedFields),
                'pdf' => $this->exportService->exportToPdf($filters, $this->selectedFields),
                'sql' => $this->exportService->exportToSql($filters),
                default => throw new Exception('Invalid format'),
            };

            $this->successMessage = __('import_export.export_success');
            $this->dispatch('closeModal');

        } catch (Exception $e) {
            $this->errorMessages = [$e->getMessage()];
            Log::error('Export failed: ' . $e->getMessage());
        }
    }

    public function toggleField($field)
    {
        if (in_array($field, $this->selectedFields)) {
            $this->selectedFields = array_filter($this->selectedFields, fn($f) => $f !== $field);
        } else {
            $this->selectedFields[] = $field;
        }
    }

    public function selectAllFields()
    {
        $this->selectedFields = array_keys($this->exportService->getExportableFields());
    }

    public function clearFields()
    {
        $this->selectedFields = [];
    }

    public function render()
    {
        return view('livewire.modals.patients.patient-export-modal', [
            'exportableFields' => $this->exportService->getExportableFields(),
            'templates' => $this->exportService->getExportTemplates(),
        ]);
    }
}
