<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Task;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\Repositories\Task\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItUpdatesTheTaskStatus(): void
    {
        $task = (new TaskRepository())->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $response = $this->putJson("/api/tasks/{$task->getAttribute('id')}", [
            'status' => 'completed',
        ]);

        $response->assertOk();
        $response->assertJsonPath('status', 'completed');
        $response->assertJsonPath('title', 'Buy groceries');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->getAttribute('id'),
            'status' => 'completed',
        ]);
    }

    public function testItUpdatesTheTaskTitle(): void
    {
        $task = (new TaskRepository())->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $response = $this->putJson("/api/tasks/{$task->getAttribute('id')}", [
            'title' => 'Buy groceries and milk',
        ]);

        $response->assertOk();
        $response->assertJsonPath('title', 'Buy groceries and milk');
        $response->assertJsonPath('status', 'active');
    }

    public function testItRejectsAnInvalidStatus(): void
    {
        $task = (new TaskRepository())->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $response = $this->putJson("/api/tasks/{$task->getAttribute('id')}", [
            'status' => 'not-a-real-status',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['status']);
    }

    public function testItReturnsNotFoundForANonExistentTask(): void
    {
        $response = $this->putJson('/api/tasks/00000000-0000-0000-0000-000000000000', [
            'status' => 'completed',
        ]);

        $response->assertNotFound();
    }
}
