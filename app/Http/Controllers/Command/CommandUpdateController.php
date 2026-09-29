<?php
declare(strict_types=1);

namespace App\Http\Controllers\Command;

use App\DataTransferObjects\Command\CommandUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Command\CommandUpdateRequest;
use App\Repositories\Interfaces\Command\CommandRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandUpdateController extends Controller
{
    public function __construct(
        private readonly CommandRepositoryInterface $commandRepository
    ) {
    }

    public function __invoke(CommandUpdateRequest $request, string $command): JsonResponse
    {
        $dto = new CommandUpdateDto(
            $request->validated('title'),
            $request->validated('rows')
        );

        $updated = $this->commandRepository->update($this->organizationId(), $command, $dto);

        return response()->json($updated);
    }
}
