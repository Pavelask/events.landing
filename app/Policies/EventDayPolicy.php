<?php

namespace App\Policies;

use App\Models\EventDay;
use App\Models\User;

class EventDayPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, EventDay $record): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, EventDay $record): bool { return true; }
    public function delete(User $user, EventDay $record): bool { return true; }
    public function restore(User $user, EventDay $record): bool { return true; }
    public function forceDelete(User $user, EventDay $record): bool { return true; }
}
