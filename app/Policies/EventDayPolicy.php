<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EventDay;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventDayPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EventDay');
    }

    public function view(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('View:EventDay');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EventDay');
    }

    public function update(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('Update:EventDay');
    }

    public function delete(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('Delete:EventDay');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EventDay');
    }

    public function restore(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('Restore:EventDay');
    }

    public function forceDelete(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('ForceDelete:EventDay');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EventDay');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EventDay');
    }

    public function replicate(AuthUser $authUser, EventDay $eventDay): bool
    {
        return $authUser->can('Replicate:EventDay');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EventDay');
    }

}