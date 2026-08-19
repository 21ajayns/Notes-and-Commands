<?php
declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskGetRequest;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskGetController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(TaskGetRequest $request): JsonResponse
    {
        $tasks = $this->taskRepository->all(
            $request->validated('category'),
            $request->validated('status')
        );

        return response()->json($tasks);
    }
}
