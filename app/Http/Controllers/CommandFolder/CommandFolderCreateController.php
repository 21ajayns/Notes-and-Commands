<?php
declare(strict_types=1);

namespace App\Http\Controllers\CommandFolder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\CommandFolder\CommandFolderCreateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommandFolder\CommandFolderCreateRequest;
use App\Repositories\Interfaces\CommandFolder\CommandFolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CommandFolderCreateController extends Controller
{
    public function __construct(
        private readonly CommandFolderRepositoryInterface $commandFolderRepository
    ) {
    }

    public function __invoke(CommandFolderCreateRequest $request): JsonResponse
    {
        $dto = new CommandFolderCreateDto(
            $request->validated('name'),
            new CategoryEnum($request->validated('category')),
            $request->validated('folder_id')
        );

        $folder = $this->commandFolderRepository->create($dto);

        return response()->json($folder, 201);
    }
}
