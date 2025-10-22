<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BulletPoint;

class BulletPointPolicy
{
    /**
     * Optional: short-circuit check for super-admins.
     */
    public function before(User $user): ?bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        if (property_exists($user, 'is_admin') && $user->is_admin) {
            return true;
        }

        return null;
    }

    /**
     * Centralized permission key.
     */
    protected function key(): string
    {
        return 'bulletpoint';
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_bulletpoint');
    }

    public function view(User $user, BulletPoint $model): bool
    {
        return $user->can('view_bulletpoint');
    }

    public function create(User $user): bool
    {
        return $user->can('create_' . $this->key());
    }

    public function update(User $user, BulletPoint $model): bool
    {
        return $user->can('update_bulletpoint');
    }

    public function delete(User $user, BulletPoint $model): bool
    {
        return $user->can('delete_bulletpoint');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_bulletpoint');
    }

    public function forceDelete(User $user, BulletPoint $model): bool
    {
        return $user->can('force_delete_bulletpoint');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_bulletpoint');
    }

    public function restore(User $user, BulletPoint $model): bool
    {
        return $user->can('restore_bulletpoint');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_bulletpoint');
    }

    public function replicate(User $user, BulletPoint $model): bool
    {
        return $user->can('replicate_bulletpoint');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_bulletpoint');
    }
}
