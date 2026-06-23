<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_user_list()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_can_create_user()
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'role' => 'User',
            'status' => 1,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_cannot_change_email_on_update()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['email' => 'original@example.com']);

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'Admin',
            'status' => 0,
        ]);

        $response->assertRedirect(route('users.index'));
        $user->refresh();
        $this->assertEquals('original@example.com', $user->email);
        $this->assertEquals('Updated Name', $user->name);
    }
}
