<?php

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');
});

/**
 * Check if tasks page is accessible
 */
it('has task page', function () {
    $response = $this->get('/api/tasks');
    $response->assertStatus(200);
});

/**
 * Get all tasks
 */
test('it can list all tasks', function () {
    Task::factory()->count(5)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/tasks/all');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'tasks',
                'meta' => ['current_page', 'last_page', 'total']
            ]
        ]);
});

/**
 * Get tasks for the authenticated user
 */
test('it can list all tasks for owner', function () {
    Task::factory()->count(3)->create(['user_id' => $this->user->id]);

    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'tasks',
                'meta' => ['current_page', 'last_page', 'total']
            ]
        ]);
});

/**
 * Create a new task
 */
test('it can create a new task', function () {
    $payload = [
        'title'       => 'My First Task',
        'description' => 'Learn Pest Testing',
        'due_date'    => now()->addWeek()->toDateString(),
    ];

    $response = $this->postJson('/api/tasks', $payload);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'My First Task');

    $this->assertDatabaseHas('tasks', [
        'title'   => 'My First Task',
        'user_id' => $this->user->id,
    ]);
});

/**
 * Update an existing task
 */
test('it can update an existing task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $payload = [
        'title'       => 'Updated Task',
        'description' => 'Updated description',
        'due_date'    => now()->addDays(5)->toDateString(),
        'status'      => 'pending', // include if required
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $payload);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Updated Task');

    $this->assertDatabaseHas('tasks', [
        'id'    => $task->id,
        'title' => 'Updated Task',
    ]);
});

/**
 * Delete a task
 */
test('it can delete a task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(200);

    // If soft deletes are enabled:
    $this->assertSoftDeleted('tasks', [
        'id' => $task->id,
    ]);
});

/**
 * Complete a task
 */
test('it can complete an existing task', function () {
    $task = Task::factory()->create([
        'user_id' => $this->user->id,
        'status'  => 'pending',
    ]);

    $response = $this->postJson("/api/tasks/{$task->id}/complete");

    $response->assertStatus(200)
        ->assertJsonPath('data.status', 'done');

    $this->assertDatabaseHas('tasks', [
        'id'     => $task->id,
        'status' => 'done',
    ]);
});
