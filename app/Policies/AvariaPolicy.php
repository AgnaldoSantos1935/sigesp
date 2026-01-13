<?php

namespace App\Policies;

use App\Models\Avaria;
use App\Models\User;

class AvariaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Avaria $avaria): bool
    {
        // TODO: Implement DRE/Unidade check
        // return $user->unidades->contains($avaria->unidade_id);
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Avaria $avaria): bool
    {
        return true;
    }
}
