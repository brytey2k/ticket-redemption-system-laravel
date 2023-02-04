<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Log;

class UsersController extends Controller
{

    private string $logPrefix = '[' . __CLASS__ . '] ';

    public function __construct(public UserService $userService)
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();

        if($this->userService->createUser($data)) {
            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        } else {
            Log::error($this->logPrefix . 'Error was encountered while creating user');
            return redirect()
                ->route('users.create')
                ->withInput()
                ->with('error', 'Failed to create user. Please try again');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if($this->userService->updateUser($user, $data)) {
            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully');
        } else {
            Log::error($this->logPrefix . 'Error was encountered while updating user');

            return redirect()
                ->route('users.edit', [$user])
                ->withInput()
                ->with('error', 'Failed to update user. Please try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($this->userService->deleteUser($user)) {
            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully');
        } else {
            Log::error($this->logPrefix . 'Error was encountered while deleting user');

            return redirect()
                ->route('user')
                ->withInput()
                ->with('error', 'Failed to delete user. Please try again');
        }
    }

}
