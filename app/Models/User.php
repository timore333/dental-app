<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // ✅ REMOVED HasApiTokens - not needed for dental app
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'locale',
        'theme',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Get the role associated with the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the two factor settings associated with the user.
     */
    public function twoFactorSetting(): HasOne
    {
        return $this->hasOne(TwoFactorSetting::class);
    }

    // ============================================================
    // ROLE CHECKING METHODS
    // ============================================================

    /**
     * Check if user has a specific role by name or instance.
     *
     * @param string|Role $role Role name or Role instance
     * @return bool
     */
    public function hasRole(string|Role $role): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_string($role)) {
            // Case-insensitive comparison
            return strtolower($this->role->name) === strtolower($role);
        }

        return $this->role->id === $role->id;
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param string|Role ...$roles
     * @return bool
     */
    public function hasAnyRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has a specific permission.
     *
     * @param string|Permission $permission
     * @return bool
     */
    public function hasPermission(string|Permission $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            return false;
        }

        return $this->role->permissions()->where('permissions.id', $permission->id)->exists();
    }

    // ============================================================
    // ROLE TYPE CHECK METHODS
    // ============================================================

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Check if user is a doctor.
     */
    public function isDoctor(): bool
    {
        return $this->hasRole('Doctor');
    }

    /**
     * Check if user is a receptionist.
     */
    public function isReceptionist(): bool
    {
        return $this->hasRole('Receptionist');
    }

    /**
     * Check if user is an accountant.
     */
    public function isAccountant(): bool
    {
        return $this->hasRole('Accountant');
    }

    /**
     * Get role name as string (for middleware compatibility).
     *
     * @return string|null
     */
    public function getRoleNameAttribute(): ?string
    {
        return $this->role?->name;
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Get the user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email;
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope to filter only admin users.
     */
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) {
            $roleQuery->where('name', 'Admin');
        });
    }

    /**
     * Scope to filter only doctor users.
     */
    public function scopeDoctor(Builder $query): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) {
            $roleQuery->where('name', 'Doctor');
        });
    }

    /**
     * Scope to filter only receptionist users.
     */
    public function scopeReceptionist(Builder $query): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) {
            $roleQuery->where('name', 'Receptionist');
        });
    }

    /**
     * Scope to filter only accountant users.
     */
    public function scopeAccountant(Builder $query): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) {
            $roleQuery->where('name', 'Accountant');
        });
    }

    /**
     * Scope to filter only active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
