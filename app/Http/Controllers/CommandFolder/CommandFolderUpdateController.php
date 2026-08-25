<?php
declare(strict_types=1);

namespace App\Http\Controllers\CommandFolder;

use App\DataTransferObjects\CommandFolder\CommandFolderUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommandFolder\CommandFolderUpdateRequest;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandFolderUpdateController extends Controller
{
    public function __construct(
        private readonly CommandFolderRepositoryInterface $commandFolderRepository
    ) {
    }

    public function __invoke(CommandFolderUpdateRequest $request, string $commandFolder): JsonResponse
    {
        $dto = new CommandFolderUpdateDto(
            $request->validated('name')
        );

        $updated = $this->commandFolderRepository->update($commandFolder, $dto);

        return response()->json($updated);
    }
}
