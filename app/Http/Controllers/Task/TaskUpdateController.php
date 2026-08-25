<?php
declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Constants\TaskStatusEnum;
use App\DataTransferObjects\Task\TaskUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskUpdateController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(TaskUpdateRequest $request, string $task): JsonResponse
    {
        $status = $request->validated('status');

        $dto = new TaskUpdateDto(
            $request->validated('title'),
            $status !== null ? new TaskStatusEnum($status) : null
        );

        $updated = $this->taskRepository->update($task, $dto);

        return response()->json($updated);
    }
}
