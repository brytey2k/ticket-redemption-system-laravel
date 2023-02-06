<?php

namespace App\Services;

use App\Models\User;
use Hash;

class UserService
{

    private string $logPrefix = '[' . __CLASS__ . '] ';

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        info($this->logPrefix . 'User created successfully', [$user]);
        return $user;
    }

    /**
     * @throws \Throwable
     */
    public function updateUser(User $user, array $data): bool
    {
        if(is_null($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $res = $user->updateOrFail($data);

        info($this->logPrefix . 'User updated successfully', [$user]);

        return $res;
    }

    /**
     * @throws \Throwable
     */
    public function deleteUser(User $user): ?bool
    {
        $res = $user->deleteOrFail();

        info($this->logPrefix . 'User deleted successfully', [$user]);

        return $res;
    }

}
