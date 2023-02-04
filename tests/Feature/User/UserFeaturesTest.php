<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFeaturesTest extends TestCase
{

    use RefreshDatabase;

    public function testUsersPageCannotBeViewedByNonAdmins() {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function testUsersPageCanBeViewedByAdmins() {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->get(route('users.index'));

        $response->assertOk();
    }

    public function testUserCanBeCreated() {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'newuser@email.com',
                'password' => 'mypassword',
                'password_confirmation' => 'mypassword',
                'role' => 'admin',
            ]);

        $response
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');
    }

    public function testUserCanBeUpdatedWithPassword() {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->patch(route('users.update', $user), [
                'name' => 'New User',
                'email' => 'newuser@email.com',
                'password' => 'mypassword',
                'password_confirmation' => 'mypassword',
                'role' => 'admin',
            ]);

        $response
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');
    }

    public function testUserCanBeUpdatedWithoutPassword() {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->patch(route('users.update', $user), [
                'name' => 'New User',
                'email' => 'newuser@email.com',
                'password' => '',
                'password_confirmation' => '',
                'role' => 'admin',
            ]);

        $response
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');
    }

    public function testUserCanBeDeleted() {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)
            ->delete(route('users.destroy', [$user]));

        $response
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');
    }

}
