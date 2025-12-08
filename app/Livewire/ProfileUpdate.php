<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileUpdate extends Component
{
    public $name = '';
    public $email = '';
    public $language = 'en';
    public $darkMode = false;
    public $tab = 'profile';
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->language = session('locale', 'en');
        $this->darkMode = session('dark_mode', false);
    }

    public function render()
    {
        return view('livewire.profile-update');
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|in:en,ar',
        ]);

        $user = Auth::user();
        $user->update(['name' => $this->name]);

        session(['locale' => $this->language, 'dark_mode' => $this->darkMode]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required|current_password',
            'newPassword' => [
                'required',
                'min:8',
                'confirmed',
                'different:currentPassword',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/',
            ],
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($this->newPassword)]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('success', 'Password changed successfully!');
    }
}
