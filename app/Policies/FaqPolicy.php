<?php

namespace App\Policies;

use App\Models\Faq;
use App\Models\User;

class FaqPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Faq $record): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Faq $record): bool { return true; }
    public function delete(User $user, Faq $record): bool { return true; }
    public function restore(User $user, Faq $record): bool { return true; }
    public function forceDelete(User $user, Faq $record): bool { return true; }
}
