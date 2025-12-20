<?php

namespace App\Livewire\Modals\Patients;


use AllowDynamicProperties;
use Cloudstudio\Modal\LivewireModal;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Services\Patients\PatientImportExportService;
use Exception;
use Illuminate\Support\Facades\Log;

#[AllowDynamicProperties] class PatientImportModal extends LivewireModal
{
    use WithFileUploads;

    #[Validate(['file' => 'required|file|mimes:xlsx,xls,csv|max:5120'])]
    public $importFile;

    public $importMode = 'create-new';
    public $skipDuplicates = false;
    public $isProcessing = false;
    public $importResults = [];
    public $errorMessages = [];
    public $successMessage = '';

    private $importService;

    public function boot(PatientImportExportService $service)
    {
        $this->imortService = $service;
    }

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function import()
    {
        try {
            $this->validate();

            $this->isProcessing = true;

            $results = $this->importService->importFromFile(
                $this->importFile,
                $this->skipDuplicates,
                $this->importMode
            );

            $this->importResults = $results;
            $this->successMessage = __('import_export.import_success', [
                'created' => $results['created'],
                'updated' => $results['updated'],
                'skipped' => $results['skipped'],
            ]);

            $this->importFile = null;

            $this->dispatch('patientsImported', total: $results['total']);

            // Close modal after 2 seconds
            $this->dispatch('closeModal');

        } catch (Exception $e) {
            $this->errorMessages = [$e->getMessage()];
            Log::error('Import failed: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.modals.patients.patient-import-modal');
    }
}
