<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $sortBy = 'name';

    #[Url]
    public string $sortDirection = 'asc';

    #[Url]
    public string $category = '';

    #[Url]
    public string $status = '';

    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?Procedure $editingProcedure = null;

    protected $listeners = ['procedureCreated', 'procedureUpdated', 'procedureDeleted'];

    public function procedureCreated()
    {
        $this->resetPage();
        $this->dispatch('notify', message: __('Procedure created successfully'));
    }

    public function procedureUpdated()
    {
        $this->dispatch('notify', message: __('Procedure updated successfully'));
    }

    public function procedureDeleted()
    {
        $this->resetPage();
        $this->dispatch('notify', message: __('Procedure deleted successfully'));
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function openEditModal(Procedure $procedure)
    {
        $this->editingProcedure = $procedure;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingProcedure = null;
    }

    public function deleteProcedure(Procedure $procedure)
    {
        $procedure->delete();
        $this->procedureDeleted();
    }

    public function render()
    {
        $procedures = Procedure::query()
            ->when($this->search, fn($query) =>
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->description}%")
            )
            ->when($this->category, fn($query) =>
                $query->where('category', $this->category)
            )
            ->when($this->status !== '', fn($query) =>
                $query->where('is_active', $this->status === 'active')
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        $categories = Procedure::distinct('category')->pluck('category');

        return view('livewire.procedures.index', [
            'procedures' => $procedures,
            'categories' => $categories,
        ]);
    }
}
