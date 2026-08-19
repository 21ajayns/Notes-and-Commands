<?php
declare(strict_types=1);

namespace App\Repositories\Interfaces\Task;

use App\DataTransferObjects\Task\TaskCreateDto;
use App\DataTransferObjects\Task\TaskUpdateDto;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function create(TaskCreateDto $createDto): Task;

    public function all(?string $category = null, ?string $status = null): Collection;

    public function update(string $id, TaskUpdateDto $updateDto): Task;

    public function delete(string $id): void;
}
