<?php

namespace App\Observers;

use App\Models\User;
use Log;

class UserObserver
{

    private string $prefix = '[' . __CLASS__ . '] ';

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info($this->prefix . 'User created: ', [$user]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info($this->prefix . 'User updated: ', [$user]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user)
    {
        Log::info($this->prefix . 'User soft-deleted: ', [$user]);
    }

}
