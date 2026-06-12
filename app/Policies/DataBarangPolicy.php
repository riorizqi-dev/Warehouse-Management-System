<?php

namespace App\Policies;

use App\Models\DataBarang;
use App\Models\User;

class DataBarangPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DataBarang $dataBarang): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManageWarehouse();
    }

    public function update(User $user, DataBarang $dataBarang): bool
    {
        return $user->canManageWarehouse();
    }

    public function delete(User $user, DataBarang $dataBarang): bool
    {
        return $user->isSuperAdmin();
    }
}
