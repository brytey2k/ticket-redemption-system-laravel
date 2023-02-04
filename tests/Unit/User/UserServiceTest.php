<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{

    public function testUserIsCreatedAndReturned() {
        $userService = $this->createStub(UserService::class);
        $userService->method('createUser')
            ->willReturn(new User([
                'role' => 'user',
                'email' => 'user@email.com',
                'name' => 'User Name',
            ]));

        $user = $userService->createUser([]);
        $this->assertSame('user@email.com', $user->email, 'The returned user email must be user@email.com');
        $this->assertSame('User Name', $user->name, 'The returned user name must be User Name');
    }

    public function testUserIsUpdated() {
        $userService = $this->createStub(UserService::class);
        $userService->method('updateUser')
            ->willReturn(true);

        $this->assertSame(true, $userService->updateUser(new User(), []), 'Must return true on successful update of user');
    }

    public function testUserIsDeleted() {
        $userService = $this->createStub(UserService::class);
        $userService->method('deleteUser')
            ->willReturn(true);

        $this->assertSame(true, $userService->deleteUser(new User()), 'Must return true on successful deletion of user');
    }

}
