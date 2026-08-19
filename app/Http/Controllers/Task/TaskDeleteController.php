<?php
declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;
use Illuminate\Http\Response;

class TaskDeleteController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(string $task): Response
    {
        $this->taskRepository->delete($task);

        return response()->noContent();
    }
}
