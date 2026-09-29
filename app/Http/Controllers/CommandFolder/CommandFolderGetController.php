<?php
declare(strict_types=1);

namespace App\Http\Controllers\CommandFolder;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommandFolder\CommandFolderGetRequest;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandFolderGetController extends Controller
{
    public function __construct(
        private readonly CommandFolderRepositoryInterface $commandFolderRepository
    ) {
    }

    public function __invoke(CommandFolderGetRequest $request): JsonResponse
    {
        $folders = $this->commandFolderRepository->all(
            $this->organizationId(),
            $request->validated('folder_id'),
            $request->validated('category')
        );

        return response()->json($folders);
    }
}
