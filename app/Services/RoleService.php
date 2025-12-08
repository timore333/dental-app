<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class RoleService
{
    /**
     * Assign a role to a user.
     */
    public function assignRoleToUser(User $user, string|Role $role): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return false;
        }

        $user->update(['role_id' => $role->id]);

        return true;
    }

    /**
     * Grant a permission to a role.
     */
    public function grantPermissionToRole(string|Role $role, string|Permission $permission): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return false;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            return false;
        }

        if (!$role->hasPermission($permission)) {
            $role->grantPermission($permission);
        }

        return true;
    }

    /**
     * Revoke a permission from a role.
     */
    public function revokePermissionFromRole(string|Role $role, string|Permission $permission): bool
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return false;
        }

        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            return false;
        }

        $role->revokePermission($permission);

        return true;
    }

    /**
     * Check if a user has a specific permission.
     */
    public function userHasPermission(User $user, string|Permission $permission): bool
    {
        return $user->hasPermission($permission);
    }

    /**
     * Check if a user has a specific role.
     */
    public function userHasRole(User $user, string|Role $role): bool
    {
        return $user->hasRole($role);
    }

    /**
     * Get all users with a specific role.
     */
    public function getUsersByRole(string|Role $role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return collect();
        }

        return User::where('role_id', $role->id)->get();
    }

    /**
     * Get all permissions for a role.
     */
    public function getRolePermissions(string|Role $role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return collect();
        }

        return $role->permissions;
    }
}
