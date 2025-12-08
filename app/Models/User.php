<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
     /** @use HasFactory<\Database\Factories\UserFactory> */
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

    /**
     * Check if user has a specific role by name or instance.
     */
    public function hasRole(string|Role $role): bool
    {
        if (is_string($role)) {
            return $this->role && $this->role->name === $role;
        }

        return $this->role && $this->role->id === $role->id;
    }

    /**
     * Check if user has any of the given roles.
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
     */
    public function hasPermission(string|Permission $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            return false;
        }

        return $this->role->permissions->contains($permission);
    }




    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }
  // Scope to filter only admin users
    public function scopeAdmin(Builder $query): Builder
    {
        return $query->whereHas('role', function (Builder $roleQuery) {
            $roleQuery->where('name', 'Admin');
        });
    }


    /**
     * Check if user is a doctor
     */
    public function isDoctor(): bool
    {
       return $this->hasRole('Doctor');
    }

    /**
     * Check if user is a receptionist
     */
    public function isReceptionist(): bool
    {
        return $this->hasRole('Receptionist');
    }

    /**
     * Check if user is an accountant
     */
    public function isAccountant(): bool
    {
        return $this->hasRole('Accountant');
    }


    /**
     * Get the user's display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->email;
    }




}
