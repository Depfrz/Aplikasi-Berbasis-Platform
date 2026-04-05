<?php

namespace App\Policies;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DosenPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Dosen $dosen)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Dosen $dosen)
    {
        return $user->role === 'admin' || $user->email === $dosen->email;
    }

    public function delete(User $user, Dosen $dosen)
    {
        return $user->role === 'admin' || $user->email === $dosen->email;
    }

    public function restore(User $user, Dosen $dosen)
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Dosen $dosen)
    {
        return $user->role === 'admin';
    }
}
