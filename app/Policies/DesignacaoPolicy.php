<?php

namespace App\Policies;

use App\Models\Designacao;
use App\Models\User;

class DesignacaoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Designacao $designacao): bool
    {
        return $user->tenant_id === $designacao->tenant_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Designacao $designacao): bool
    {
        return $user->tenant_id === $designacao->tenant_id;
    }
}
