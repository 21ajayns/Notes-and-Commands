<?php
declare(strict_types=1);

namespace Tests\Feature\Repositories\Task;

use App\Constants\CategoryEnum;
use App\Constants\TaskStatusEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\DataTransferObjects\Task\TaskUpdateDto;
use App\Repositories\Task\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatePersistsATaskAsActiveByDefault(): void
    {
        $task = (new TaskRepository())->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $this->assertSame('active', $task->getAttribute('status'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->getAttribute('id'),
            'title' => 'Buy groceries',
            'category' => 'personal',
            'status' => 'active',
        ]);
    }

    public function testAllReturnsEveryTaskWhenNoFiltersGiven(): void
    {
        $repository = new TaskRepository();

        $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));
        $repository->create(new TaskCreateDto('Finish report', CategoryEnum::OFFICE()));

        $tasks = $repository->all();

        $this->assertCount(2, $tasks);
    }

    public function testAllFiltersByCategoryWhenGiven(): void
    {
        $repository = new TaskRepository();

        $office = $repository->create(new TaskCreateDto('Finish report', CategoryEnum::OFFICE()));
        $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $tasks = $repository->all('office');

        $this->assertCount(1, $tasks);
        $this->assertSame($office->getAttribute('id'), $tasks->first()->getAttribute('id'));
    }

    public function testAllFiltersByStatusWhenGiven(): void
    {
        $repository = new TaskRepository();

        $active = $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));
        $completed = $repository->create(new TaskCreateDto('Finish report', CategoryEnum::OFFICE()));
        $repository->update($completed->getAttribute('id'), new TaskUpdateDto(null, TaskStatusEnum::COMPLETED()));

        $tasks = $repository->all(null, 'active');

        $this->assertCount(1, $tasks);
        $this->assertSame($active->getAttribute('id'), $tasks->first()->getAttribute('id'));
    }

    public function testUpdateChangesOnlyTheGivenFields(): void
    {
        $repository = new TaskRepository();

        $task = $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $updated = $repository->update($task->getAttribute('id'), new TaskUpdateDto(null, TaskStatusEnum::COMPLETED()));

        $this->assertSame('Buy groceries', $updated->getAttribute('title'));
        $this->assertSame('completed', $updated->getAttribute('status'));
    }

    public function testUpdateThrowsWhenTheTaskDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new TaskRepository())->update('00000000-0000-0000-0000-000000000000', new TaskUpdateDto('Title', null));
    }

    public function testDeleteRemovesTheTask(): void
    {
        $repository = new TaskRepository();

        $task = $repository->create(new TaskCreateDto('Buy groceries', CategoryEnum::PERSONAL()));

        $repository->delete($task->getAttribute('id'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->getAttribute('id')]);
    }

    public function testDeleteThrowsWhenTheTaskDoesNotExist(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        (new TaskRepository())->delete('00000000-0000-0000-0000-000000000000');
    }
}
