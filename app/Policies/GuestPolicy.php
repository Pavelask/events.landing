<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;

class GuestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Guest $guest): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Guest $guest): bool
    {
        return true;
    }

    public function delete(User $user, Guest $guest): bool
    {
        return true;
    }

    public function restore(User $user, Guest $guest): bool
    {
        return true;
    }

    public function forceDelete(User $user, Guest $guest): bool
    {
        return true;
    }
}
