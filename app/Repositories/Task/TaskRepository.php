<?php
declare(strict_types=1);

namespace App\Repositories\Task;

use App\Constants\TaskStatusEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\DataTransferObjects\Task\TaskUpdateDto;
use App\Models\Task\Task;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(TaskCreateDto $createDto): Task
    {
        $task = new Task();
        $task->setAttribute('organization_id', $createDto->getOrganizationId());
        $task->setAttribute('title', $createDto->getTitle());
        $task->setAttribute('category', $createDto->getCategory()->getValue());
        $task->setAttribute('status', TaskStatusEnum::ACTIVE()->getValue());

        $task->save();

        return $task;
    }

    public function all(string $organizationId, ?string $category = null, ?string $status = null): Collection
    {
        return $this->forOrganization($organizationId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->when($status !== null, fn ($query) => $query->where('status', $status))
            ->get();
    }

    public function update(string $organizationId, string $id, TaskUpdateDto $updateDto): Task
    {
        $task = $this->forOrganization($organizationId)->findOrFail($id);

        if ($updateDto->getTitle() !== null) {
            $task->setAttribute('title', $updateDto->getTitle());
        }

        if ($updateDto->getStatus() !== null) {
            $task->setAttribute('status', $updateDto->getStatus()->getValue());
        }

        $task->save();

        return $task;
    }

    public function delete(string $organizationId, string $id): void
    {
        $this->forOrganization($organizationId)->findOrFail($id)->delete();
    }

    public function deleteAll(string $organizationId, ?string $category = null): void
    {
        $this->forOrganization($organizationId)
            ->when($category !== null, fn ($query) => $query->where('category', $category))
            ->delete();
    }

    private function forOrganization(string $organizationId): Builder
    {
        return Task::query()->where('organization_id', $organizationId);
    }
}
