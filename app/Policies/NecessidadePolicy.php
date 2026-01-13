<?php

namespace App\Policies;

use App\Models\Necessidade;
use App\Models\User;

class NecessidadePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Necessidade $necessidade): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Necessidade $necessidade): bool
    {
        return true;
    }
}
