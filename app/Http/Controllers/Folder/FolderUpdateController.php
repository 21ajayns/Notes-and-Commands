<?php
declare(strict_types=1);

namespace App\Http\Controllers\Folder;

use App\DataTransferObjects\Folder\FolderUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\FolderUpdateRequest;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class FolderUpdateController extends Controller
{
    public function __construct(
        private readonly FolderRepositoryInterface $folderRepository
    ) {
    }

    public function __invoke(FolderUpdateRequest $request, string $folder): JsonResponse
    {
        $dto = new FolderUpdateDto(
            $request->validated('name')
        );

        $updated = $this->folderRepository->update($folder, $dto);

        return response()->json($updated);
    }
}
