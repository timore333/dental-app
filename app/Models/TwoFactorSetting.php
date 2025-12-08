<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'secret',
        'enabled',
        'backup_codes',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'backup_codes' => 'array',
            'enabled' => 'boolean',
            'secret' => 'encrypted',
        ];
    }

    /**
     * Get the user associated with this two factor setting.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if 2FA is enabled for this user.
     */
    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    /**
     * Get backup codes count.
     */
    public function getBackupCodesCount(): int
    {
        return count($this->backup_codes ?? []);
    }

    /**
     * Check if a backup code is valid and remove it.
     */
    public function useBackupCode(string $code): bool
    {
        if (!$this->backup_codes) {
            return false;
        }

        $key = array_search($code, $this->backup_codes);

        if ($key !== false) {
            unset($this->backup_codes[$key]);
            $this->save();
            return true;
        }

        return false;
    }
}
