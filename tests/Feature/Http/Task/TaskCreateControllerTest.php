<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItCreatesATaskAsActive(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Buy groceries',
            'category' => 'personal',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('title', 'Buy groceries');
        $response->assertJsonPath('category', 'personal');
        $response->assertJsonPath('status', 'active');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Buy groceries',
            'category' => 'personal',
            'status' => 'active',
        ]);
    }

    public function testItRejectsAMissingTitle(): void
    {
        $response = $this->postJson('/api/tasks', [
            'category' => 'personal',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['title']);
    }

    public function testItRejectsAnInvalidCategory(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Buy groceries',
            'category' => 'not-a-real-category',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['category']);
    }
}
