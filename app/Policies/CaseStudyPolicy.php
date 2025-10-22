<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CaseStudy;

class CaseStudyPolicy
{
    /**
     * Optional: allow super admins (role/flag) to bypass checks.
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
        return 'case_study';
    }

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_' . $this->key());
    }

    public function view(User $user, CaseStudy $model): bool
    {
        return $user->can('view_' . $this->key());
    }

    public function create(User $user): bool
    {
        return $user->can('create_' . $this->key());
    }

    public function update(User $user, CaseStudy $model): bool
    {
        return $user->can('update_' . $this->key());
    }

    public function delete(User $user, CaseStudy $model): bool
    {
        return $user->can('delete_' . $this->key());
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_' . $this->key());
    }

    public function forceDelete(User $user, CaseStudy $model): bool
    {
        return $user->can('force_delete_' . $this->key());
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_' . $this->key());
    }

    public function restore(User $user, CaseStudy $model): bool
    {
        return $user->can('restore_' . $this->key());
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_' . $this->key());
    }

    public function replicate(User $user, CaseStudy $model): bool
    {
        return $user->can('replicate_' . $this->key());
    }

    public function reorder(User $user): bool
    {
        return $user->can('reorder_' . $this->key());
    }
}
