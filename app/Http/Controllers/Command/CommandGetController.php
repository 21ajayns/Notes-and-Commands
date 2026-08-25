<?php
declare(strict_types=1);

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Controller;
use App\Http\Requests\Command\CommandGetRequest;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandGetController extends Controller
{
    public function __construct(
        private readonly CommandRepositoryInterface $commandRepository
    ) {
    }

    public function __invoke(CommandGetRequest $request): JsonResponse
    {
        $commands = $this->commandRepository->all(
            $request->validated('folder_id'),
            $request->validated('category')
        );

        return response()->json($commands);
    }
}
