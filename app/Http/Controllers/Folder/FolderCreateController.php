<?php
declare(strict_types=1);

namespace App\Http\Controllers\Folder;

use App\Constants\CategoryEnum;
use App\DataTransferObjects\Folder\FolderCreateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\FolderCreateRequest;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class FolderCreateController extends Controller
{
    public function __construct(
        private readonly FolderRepositoryInterface $folderRepository
    ) {
    }

    public function __invoke(FolderCreateRequest $request): JsonResponse
    {
        $dto = new FolderCreateDto(
            $request->validated('name'),
            new CategoryEnum($request->validated('category')),
            $request->validated('folder_id')
        );

        $folder = $this->folderRepository->create($dto);

        return response()->json($folder, 201);
    }
}