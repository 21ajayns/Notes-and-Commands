<?php
declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskClearRequest;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Http\Response;

class TaskClearController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(TaskClearRequest $request): Response
    {
        $this->taskRepository->deleteAll($this->organizationId(), $request->validated('category'));

        return response()->noContent();
    }
}
