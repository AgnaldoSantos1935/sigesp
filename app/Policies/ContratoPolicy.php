<?php

namespace App\Policies;

use App\Models\Contrato;
use App\Models\User;

class ContratoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contrato $contrato): bool
    {
        return $user->tenant_id === $contrato->tenant_id;
    }

    public function create(User $user): bool
    {
        // TODO: Implement permission check (e.g. $user->can('create_contratos'))
        return true;
    }

    public function update(User $user, Contrato $contrato): bool
    {
        return $user->tenant_id === $contrato->tenant_id;
    }

    public function delete(User $user, Contrato $contrato): bool
    {
        return $user->tenant_id === $contrato->tenant_id;
    }
}
