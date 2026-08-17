<?php
declare(strict_types=1);

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\FolderGetRequest;
use App\Repositories\Interfaces\Folder\FolderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class FolderGetController extends Controller
{
    public function __construct(
        private readonly FolderRepositoryInterface $folderRepository
    ) {
    }

    public function __invoke(FolderGetRequest $request): JsonResponse
    {
        $folders = $this->folderRepository->all(
            $request->validated('folder_id'),
            $request->validated('category')
        );

        return response()->json($folders);
    }
}
