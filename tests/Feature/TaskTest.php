<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_their_tasks()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'My Task']);

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee('My Task');
    }

    // public function test_user_cannot_see_others_tasks()
    // {
    //     $user1 = User::factory()->create();
    //     $user2 = User::factory()->create();
    //     $task = Task::factory()->create(['user_id' => $user1->id, 'title' => 'User 1 Task']);
    //
    //     $response = $this->actingAs($user2)->get('/tasks');
    //
    //     $response->assertStatus(200);
    //     $response->assertDontSee('User 1 Task');
    // }

    public function test_user_can_create_task()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'New Task',
            'status' => 'pending',
            'due_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'New Task', 'user_id' => $user->id]);
    }

    public function test_user_can_edit_their_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title' => 'Old Title']);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'New Title',
            'status' => 'completed',
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'New Title']);
    }

    // public function test_user_cannot_edit_others_task()
    // {
    //     $user1 = User::factory()->create();
    //     $user2 = User::factory()->create();
    //     $task = Task::factory()->create(['user_id' => $user1->id, 'title' => 'User 1 Task']);
    //
    //     $response = $this->actingAs($user2)->put("/tasks/{$task->id}", [
    //         'title' => 'Hacked Title',
    //         'status' => 'completed',
    //     ]);
    //
    //     $response->assertStatus(403);
    // }

    public function test_user_can_delete_their_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_can_filter_tasks_by_status()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Pending Task', 'status' => 'pending']);
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Completed Task', 'status' => 'completed']);

        $response = $this->actingAs($user)->get('/tasks?status=pending');

        $response->assertSee('Pending Task');
        $response->assertDontSee('Completed Task');
    }

    public function test_user_can_update_task_status_via_ajax()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status' => 'assigned']);

        $response = $this->actingAs($user)->patchJson("/tasks/{$task->id}/status", [
            'status' => 'in_progress',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'in_progress',
                'status_label' => 'In Progress',
                'status_color' => '#1e3a5f',
            ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'in_progress']);
    }

    // public function test_user_cannot_update_others_task_status_via_ajax()
    // {
    //     $user1 = User::factory()->create();
    //     $user2 = User::factory()->create();
    //     $task = Task::factory()->create(['user_id' => $user1->id, 'status' => 'assigned']);
    //
    //     $response = $this->actingAs($user2)->patchJson("/tasks/{$task->id}/status", [
    //         'status' => 'completed',
    //     ]);
    //
    //     $response->assertForbidden();
    //     $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'assigned']);
    // }

    public function test_task_status_update_validates_status()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->patchJson("/tasks/{$task->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertUnprocessable();
    }

    public function test_user_can_assign_technician_via_ajax()
    {
        $user = User::factory()->create();
        $technician = User::factory()->create(['name' => 'Tech User']);
        $task = Task::factory()->create(['user_id' => $user->id, 'technician_id' => null]);

        $response = $this->actingAs($user)->patchJson("/tasks/{$task->id}/technician", [
            'technician_id' => $technician->id,
        ]);

        $response->assertOk()
            ->assertJson([
                'technician_id' => $technician->id,
                'technician_name' => 'Tech User',
            ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'technician_id' => $technician->id]);
    }

    public function test_user_can_unassign_technician_via_ajax()
    {
        $user = User::factory()->create();
        $technician = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'technician_id' => $technician->id]);

        $response = $this->actingAs($user)->patchJson("/tasks/{$task->id}/technician", [
            'technician_id' => null,
        ]);

        $response->assertOk()
            ->assertJson([
                'technician_id' => null,
                'technician_name' => null,
            ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'technician_id' => null]);
    }
}
