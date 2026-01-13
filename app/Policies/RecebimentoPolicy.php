<?php

namespace App\Policies;

use App\Models\Recebimento;
use App\Models\User;

class RecebimentoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recebimento $recebimento): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recebimento $recebimento): bool
    {
        return true;
    }
}
