<?php

namespace App\Services;

use App\Models\User;
use Hash;

class UserService
{

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function updateUser(User $user, array $data): bool
    {
        if(is_null($data['password'])) {
            unset($data['password']);
        }

        return $user->update($data);
    }

    public function deleteUser(User $user): ?bool
    {
        return $user->delete();
    }

}
