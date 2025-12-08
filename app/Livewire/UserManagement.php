<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $perPage = 15;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $showModal = false;
    public $isEditing = false;
    public $userId = null;
    public $formData = [];

    // Bulk actions
    public $selectedUsers = [];
    public $selectAll = false;

    protected $rules = [
        'formData.name' => 'required|string|max:255',
        'formData.email' => 'required|email|unique:users,email',
        'formData.role_id' => 'required|exists:roles,id',
        'formData.password' => 'nullable|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
    ];

    public function render()
    {
        $query = User::with('role');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        if ($this->roleFilter) {
            $query->where('role_id', $this->roleFilter);
        }

        $users = $query->orderBy($this->sortBy, $this->sortDirection)
                       ->paginate($this->perPage);

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function filterByRole()
    {
        $this->resetPage();
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

    public function openCreateModal()
    {
        $this->isEditing = false;
        $this->formData = [];
        $this->showModal = true;
    }

    public function openEditModal($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->isEditing = true;
            $this->userId = $user->id;
            $this->formData = [
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
            ];
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            $this->create();
        }
    }

    public function create()
    {
        $this->validate([
            'formData.name' => 'required|string|max:255',
            'formData.email' => 'required|email|unique:users,email',
            'formData.role_id' => 'required|exists:roles,id',
            'formData.password' => 'required|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
        ]);

        User::create([
            'name' => $this->formData['name'],
            'email' => $this->formData['email'],
            'password' => Hash::make($this->formData['password']),
            'role_id' => $this->formData['role_id'],
        ]);

        $this->dispatch('user-created');
        $this->closeModal();
        $this->resetPage();
    }

    public function update()
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        $rules = [
            'formData.name' => 'required|string|max:255',
            'formData.email' => 'required|email|unique:users,email,' . $user->id,
            'formData.role_id' => 'required|exists:roles,id',
        ];

        if (!empty($this->formData['password'])) {
            $rules['formData.password'] = 'min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->formData['name'],
            'email' => $this->formData['email'],
            'role_id' => $this->formData['role_id'],
        ];

        if (!empty($this->formData['password'])) {
            $data['password'] = Hash::make($this->formData['password']);
        }

        $user->update($data);

        $this->dispatch('user-updated');
        $this->closeModal();
    }

    public function delete($userId)
    {
        User::find($userId)?->delete();
        $this->dispatch('user-deleted');
        $this->resetPage();
    }

    public function resetPassword($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $newPassword = 'Password@123';
            $user->update(['password' => Hash::make($newPassword)]);
            session()->flash('success', "Password reset to: $newPassword");
        }
    }
}
