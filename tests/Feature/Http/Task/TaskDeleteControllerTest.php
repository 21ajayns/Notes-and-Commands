<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Task;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\Repositories\Task\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItDeletesTheTask(): void
    {
        $task = (new TaskRepository())->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $response = $this->deleteJson("/api/tasks/{$task->getAttribute('id')}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->getAttribute('id')]);
    }

    public function testItReturnsNotFoundForANonExistentTask(): void
    {
        $response = $this->deleteJson('/api/tasks/00000000-0000-0000-0000-000000000000');

        $response->assertNotFound();
    }
}
