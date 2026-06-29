<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;

class TestimonialPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Testimonial $record): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Testimonial $record): bool { return true; }
    public function delete(User $user, Testimonial $record): bool { return true; }
    public function restore(User $user, Testimonial $record): bool { return true; }
    public function forceDelete(User $user, Testimonial $record): bool { return true; }
}
