<?php

namespace App\Policies;

use App\Models\Unidade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnidadePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Unidade $unidade): bool
    {
        return $user->tenant_id === $unidade->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin(); // Assumindo método helper ou verificação de role
    }

    public function update(User $user, Unidade $unidade): bool
    {
        return $user->tenant_id === $unidade->tenant_id && $user->isAdmin();
    }

    public function changeVinculo(User $user, Unidade $unidade): bool
    {
        // Apenas Admin ou Gestor autorizado pode alterar vínculos
        // A lógica pesada fica no Service, aqui apenas valida permissão
        return $user->tenant_id === $unidade->tenant_id && ($user->isAdmin() || $user->hasRole('gestor'));
    }
}
