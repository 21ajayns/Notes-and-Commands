<?php
declare(strict_types=1);

namespace App\Http\Controllers\Command;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandShowController extends Controller
{
    public function __construct(
        private readonly CommandRepositoryInterface $commandRepository
    ) {
    }

    public function __invoke(string $command): JsonResponse
    {
        return response()->json($this->commandRepository->find($this->organizationId(), $command));
    }
}
