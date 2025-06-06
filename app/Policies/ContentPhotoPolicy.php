<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ContentPhoto;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPhotoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_content::photo');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('view_content::photo');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_content::photo');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('update_content::photo');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('delete_content::photo');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_content::photo');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('force_delete_content::photo');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_content::photo');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('restore_content::photo');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_content::photo');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ContentPhoto $contentPhoto): bool
    {
        return $user->can('replicate_content::photo');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_content::photo');
    }
}
