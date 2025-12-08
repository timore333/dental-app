<?php

namespace App\Services;

use App\Models\User;
use App\Models\TwoFactorSetting;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;

class TwoFactorService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Enable 2FA for a user and return secret and QR code.
     */
    public function enableTwoFactor(User $user): array
    {
        // Generate secret key
        $secret = $this->google2fa->generateSecretKey();

        // Create or update 2FA setting
        $twoFactorSetting = TwoFactorSetting::updateOrCreate(
            ['user_id' => $user->id],
            ['secret' => $secret]
        );

        // Generate QR code
        $qrCode = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate backup codes
        $backupCodes = $this->generateBackupCodes();
        $twoFactorSetting->update(['backup_codes' => $backupCodes]);

        return [
            'secret' => $secret,
            'qr_code' => $qrCode,
            'backup_codes' => $backupCodes,
        ];
    }

    /**
     * Disable 2FA for a user.
     */
    public function disableTwoFactor(User $user): bool
    {
        $twoFactorSetting = $user->twoFactorSetting;

        if ($twoFactorSetting) {
            $twoFactorSetting->update([
                'enabled' => false,
                'secret' => null,
                'backup_codes' => null,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Verify a TOTP code.
     */
    public function verifyCode(User $user, string $code): bool
    {
        $twoFactorSetting = $user->twoFactorSetting;

        if (!$twoFactorSetting || !$twoFactorSetting->secret) {
            return false;
        }

        // Remove any spaces from code
        $code = str_replace(' ', '', $code);

        // Verify the code
        return $this->google2fa->verifyKey($twoFactorSetting->secret, $code);
    }

    /**
     * Generate backup codes for 2FA.
     */
    public function generateBackupCodes(int $count = 10): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(8));
        }

        return $codes;
    }

    /**
     * Use a backup code for authentication.
     */
    public function useBackupCode(User $user, string $code): bool
    {
        $twoFactorSetting = $user->twoFactorSetting;

        if (!$twoFactorSetting) {
            return false;
        }

        return $twoFactorSetting->useBackupCode($code);
    }

    /**
     * Check if user has 2FA enabled.
     */
    public function isTwoFactorEnabled(User $user): bool
    {
        $twoFactorSetting = $user->twoFactorSetting;

        return $twoFactorSetting && $twoFactorSetting->isEnabled();
    }

    /**
     * Confirm and enable 2FA (after user verifies code).
     */
    public function confirmTwoFactor(User $user, string $code): bool
    {
        if (!$this->verifyCode($user, $code)) {
            return false;
        }

        $user->twoFactorSetting->update(['enabled' => true]);

        return true;
    }

    /**
     * Get remaining backup codes count.
     */
    public function getBackupCodesCount(User $user): int
    {
        $twoFactorSetting = $user->twoFactorSetting;

        if (!$twoFactorSetting) {
            return 0;
        }

        return $twoFactorSetting->getBackupCodesCount();
    }
}
