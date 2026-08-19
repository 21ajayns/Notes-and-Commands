<?php
declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Task\TaskCreateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskCreateController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(TaskCreateRequest $request): JsonResponse
    {
        $dto = new TaskCreateDto(
            $request->validated('title'),
            new CategoryEnum($request->validated('category'))
        );

        $task = $this->taskRepository->create($dto);

        return response()->json($task, 201);
    }
}
