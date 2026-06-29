<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Event $record): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Event $record): bool { return true; }
    public function delete(User $user, Event $record): bool { return true; }
    public function restore(User $user, Event $record): bool { return true; }
    public function forceDelete(User $user, Event $record): bool { return true; }
}
