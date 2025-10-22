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
        return $user->can('view_any_bullet_point');
    }

    public function view(User $user, BulletPoint $model): bool
    {
        return $user->can('view_bullet_point');
    }

    public function create(User $user): bool
    {
        return $user->can('create_' . $this->key());
    }

    public function update(User $user, BulletPoint $model): bool
    {
        return $user->can('update_bullet_point');
    }

    public function delete(User $user, BulletPoint $model): bool
    {
        return $user->can('delete_bullet_point');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_bullet_point');
    }

    public function forceDelete(User $user, BulletPoint $model): bool
    {
        return $user->can('force_delete_bullet_point');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_bulle_tpoint');
    }

    public function restore(User $user, BulletPoint $model): bool
    {
        return $user->can('restore_bullet_point');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_bullet_point');
    }

    public function replicate(User $user, BulletPoint $model): bool
    {
        return $user->can('replicate_bullet_point');
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_bullet_point');
    }
}
