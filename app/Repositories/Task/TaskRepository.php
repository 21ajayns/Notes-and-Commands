<?php
declare(strict_types=1);

namespace App\Repositories\Task;

use App\Constants\TaskStatusEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\DataTransferObjects\Task\TaskUpdateDto;
use App\Models\Task\Task;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(TaskCreateDto $createDto): Task
    {
        $task = new Task();
        $task->setAttribute('title', $createDto->getTitle());
        $task->setAttribute('category', $createDto->getCategory()->getValue());
        $task->setAttribute('status', TaskStatusEnum::ACTIVE()->getValue());

        $task->save();

        return $task;
    }

    public function all(?string $category = null, ?string $status = null): Collection
    {
        return Task::query()
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->when($status !== null, fn ($query) => $query->where('status', $status))
            ->get();
    }

    public function update(string $id, TaskUpdateDto $updateDto): Task
    {
        $task = Task::query()->findOrFail($id);

        if ($updateDto->getTitle() !== null) {
            $task->setAttribute('title', $updateDto->getTitle());
        }

        if ($updateDto->getStatus() !== null) {
            $task->setAttribute('status', $updateDto->getStatus()->getValue());
        }

        $task->save();

        return $task;
    }

    public function delete(string $id): void
    {
        Task::query()->findOrFail($id)->delete();
    }
}
