<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Task;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\Repositories\Task\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskGetControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsAllTasks(): void
    {
        $repository = new TaskRepository();
        $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));
        $repository->create(new TaskCreateDto('Finish report', CategoryEnum::OFFICE()));

        $response = $this->getJson('/api/tasks');

        $response->assertOk();
        $response->assertJsonCount(2);
    }

    public function testItFiltersByCategoryAndStatus(): void
    {
        $repository = new TaskRepository();
        $office = $repository->create(new TaskCreateDto('Finish report', CategoryEnum::OFFICE()));
        $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $response = $this->getJson('/api/tasks?category=office&status=active');

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.id', $office->getAttribute('id'));
    }

    public function testItRejectsAnInvalidStatus(): void
    {
        $response = $this->getJson('/api/tasks?status=not-a-real-status');

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['status']);
    }
}
