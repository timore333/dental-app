<?php

namespace App\Livewire;

use App\Services\TwoFactorService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TwoFactorSetup extends Component
{
    public $showQrCode = false;
    public $secret = '';
    public $backupCodes = [];
    public $verificationCode = '';
    public $is2FAEnabled = false;

    protected TwoFactorService $twoFactorService;

    public function boot()
    {
        $this->twoFactorService = app(TwoFactorService::class);
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->twoFactorSetting) {
            $this->is2FAEnabled = $user->twoFactorSetting->isEnabled();
        }
    }

    public function render()
    {
        return view('livewire.two-factor-setup');
    }

    public function enable2fa()
    {
        $user = Auth::user();
        $result = $this->twoFactorService->enableTwoFactor($user);

        $this->secret = $result['secret'];
        $this->backupCodes = $result['backup_codes'];
        $this->showQrCode = true;
        $this->verificationCode = '';

        session()->flash('info', 'Scan the QR code with your authenticator app and enter the verification code.');
    }

    public function disable2fa()
    {
        $user = Auth::user();
        $this->twoFactorService->disableTwoFactor($user);

        $this->showQrCode = false;
        $this->is2FAEnabled = false;
        $this->secret = '';
        $this->backupCodes = [];
        $this->verificationCode = '';

        session()->flash('success', 'Two-factor authentication has been disabled.');
    }

    public function verifyCode()
    {
        $this->validate([
            'verificationCode' => 'required|size:6|numeric',
        ]);

        $user = Auth::user();

        if ($this->twoFactorService->confirmTwoFactor($user, $this->verificationCode)) {
            $this->is2FAEnabled = true;
            $this->showQrCode = false;
            $this->verificationCode = '';

            session()->flash('success', 'Two-factor authentication has been enabled successfully!');
        } else {
            $this->addError('verificationCode', 'Invalid verification code.');
        }
    }

    public function regenerateBackupCodes()
    {
        $user = Auth::user();
        $codes = $this->twoFactorService->generateBackupCodes();

        if ($user->twoFactorSetting) {
            $user->twoFactorSetting->update(['backup_codes' => $codes]);
            $this->backupCodes = $codes;
            session()->flash('success', 'Backup codes have been regenerated.');
        }
    }

    public function copyBackupCodes()
    {
        session()->flash('success', 'Backup codes copied to clipboard!');
    }
}
